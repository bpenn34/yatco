// Global variables to hold icon data
let png_icons = [];
let svg_icons = [];

function pageLoader(){
	// Create loader overlay
	const loaderEl = document.createElement('div');
	loaderEl.id = 'gjs-loader';
	loaderEl.style.cssText = `
		position: fixed;
		top: 0; left: 0;
		width: 100vw;
		height: 100vh;
		background: rgba(255, 255, 255, 0.9);
		z-index: 9999;
		display: flex;
		align-items: center;
		justify-content: center;
		font-family: sans-serif;
		font-size: 18px;
	`;

	loaderEl.innerHTML = '<div class="loader-spinner">Setting up the Builder...</div>';
	document.body.appendChild(loaderEl);

}
pageLoader();

document.addEventListener("DOMContentLoaded", function () {

	window.__svgWarningShown = yatcoData.svgWarningShown || false;
	window.__svgCache = {};

	const timestamp = Date.now(); // bust cache
	const basePath = yatcoData.YATCO_PLUGIN_ASSETS + 'install-assets/';

	iconsReady = false;

	fetch(`${yatcoData.YATCO_PLUGIN_ASSETS}install-assets/png-icons-file.json?cb=${timestamp}`)
		.then(res => res.json())
		.then(pngData => {
		png_icons = pngData.map(icon => ({
			...icon,
			src: basePath + icon.src
		}));
		return fetch(`${yatcoData.YATCO_PLUGIN_ASSETS}install-assets/svg-icons-file.json?cb=${timestamp}`);
		})
		.then(res => res.json())
		.then(svgData => {
		svg_icons = svgData.map(icon => ({
			...icon,
			src: basePath + icon.src
		}));

		// Preload SVGs into memory cache
		svg_icons.forEach(icon => {
			fetch(icon.src)
			.then(res => res.text())
			.then(svgText => {
				if (svgText.includes('<svg')) {
				window.__svgCache[icon.src] = svgText;
				}
			})
			.catch(err => console.warn(`❌ Failed to preload ${icon.src}:`, err));
		});

		iconsReady = true;
		// Hide loader when icons are ready
		const loader = document.getElementById('gjs-loader');
		if (loader) loader.remove();

		})
		.catch(err => {
		console.error('❌ Failed to load icon files:', err);
		});


	// Define the function that processes the content
	function processTemplateContent(content) {
		// Replace only images with class 'slider-placeholder-image' with {slider}
		content = content.replace(/<img[^>]+class=["']slider-placeholder-image["'][^>]*>/g, "{slider}");
		// Replace only images with class 'slider2-placeholder-image' with {slider2}
		content = content.replace(/<img[^>]+class=["']slider2-placeholder-image["'][^>]*>/g, "{slider2}");
		// Replace only images with class 'gallery-placeholder-image' with {gallery}
		content = content.replace(/<img[^>]+class=["']gallery-placeholder-image["'][^>]*>/g, "{gallery}");
		// Replace only images with class 'contactForm-placeholder-image' with {contactForm}
		content = content.replace(/<img[^>]+class=["']contactForm-placeholder-image["'][^>]*>/g, "{contactForm}");
		
		// Create a temporary container to manipulate the HTML as DOM
		let tempDiv = document.createElement("div");
		tempDiv.innerHTML = content;
		
		// Find all elements that have an id attribute and convert them to a class
		tempDiv.querySelectorAll("[id]").forEach(el => {
		let idVal = el.getAttribute("id");
		el.removeAttribute("id");
		el.classList.add(idVal);
		});
		
		// Return the updated HTML
		return tempDiv.innerHTML;
	}

	// Expose the function to the global scope so it can be called from elsewhere
	window.processTemplateContent = processTemplateContent;
 
	// Define the function that processes the content
	function processTemplateStyle(style) {
		const iframe = document.querySelector('#gjs iframe');
		const wrapper = iframe?.contentDocument?.getElementById('yt-builder-enqueue');

		let combinedCSS = '';

		// A function to safely replace #id with .id inside selectors
		function replaceIds(cssText) {
			return cssText.replace(/@media[^{]+\{([\s\S]*?)\}/g, (match, inner) => {
				// Process the inner CSS rules of @media separately
				const processedInner = inner.replace(/([^{}]+){/g, (m, selector) => {
					const replaced = selector.replace(/#([\w-]+)/g, '.$1');
					return replaced + '{';
				});
				return match.replace(inner, processedInner);
			}).replace(/([^@{}][^{}]*){/g, (match, selector) => {
				// Skip @media and already-processed blocks
				const replaced = selector.replace(/#([\w-]+)/g, '.$1');
				return replaced + '{';
			});
		}

		if (wrapper) {
			const styleTags = wrapper.querySelectorAll('style');
			styleTags.forEach(tag => {
				let styleContent = tag.textContent || '';
				combinedCSS += replaceIds(styleContent) + '\n';
			});
		}

		combinedCSS += replaceIds(style);
		return combinedCSS.trim();
	}

	// Expose the function to the global scope so it can be called from elsewhere
	window.processTemplateStyle = processTemplateStyle;

  	if (typeof grapesjs !== "undefined") {

		// The custom fonts retrieved from PHP Localize script.
		const customFonts = yatcoData.custom_fonts_js;

		// Define a default list of fonts (you can adjust this as needed).
		const defaultFonts = [
			{ value: 'Arial, sans-serif', name: 'Arial' },
			{ value: '"Times New Roman", serif', name: 'Times New Roman' },
			{ value: 'Georgia, serif', name: 'Georgia' },
			{ value: 'Courier, monospace', name: 'Courier' }
		];
			
		// Map the custom fonts to the structure GrapesJS expects for the dropdown
		const customFontOptions = customFonts.map(font => ({
			value: font.font_family + ', sans-serif', // Adjust as needed
			name: font.font_family,
			fileUrl: font.file_url,      // Extra property for potential use
			fontWeight: font.font_weight,
			fontStyle: font.font_style
		}));
			
		const allFonts = defaultFonts.concat(customFontOptions);

		function customCssParser(cssString) {
			const rules = [];

			// Helper: parse styles and preserve box-shadow order
			const parseStyle = (propsStr) => {
				const style = {};
				propsStr.split(';').forEach(prop => {
					const [key, val] = prop.split(':').map(p => p.trim());
					if (key && val) {
						if (key === 'box-shadow') {
							style['box-shadow'] = val; // preserve exactly
						} else {
							style[key] = val;
						}
					}
				});
				return style;
			};

			// Helper: extract balanced media query blocks
			const extractMediaBlocks = (css) => {
				const blocks = [];
				let index = 0;

				while (index < css.length) {
					const start = css.indexOf('@media', index);
					if (start === -1) break;

					const open = css.indexOf('{', start);
					if (open === -1) break;

					let i = open + 1;
					let count = 1;
					while (i < css.length && count > 0) {
						if (css[i] === '{') count++;
						else if (css[i] === '}') count--;
						i++;
					}

					const block = css.slice(start, i);
					blocks.push(block);
					index = i;
				}

				return blocks;
			};

			// Step 1: Parse media query blocks
			const mediaBlocks = extractMediaBlocks(cssString);

			mediaBlocks.forEach(block => {
				const headerMatch = block.match(/@media\s*([^{]+)\s*\{/);
				if (!headerMatch) return;

				const mediaCondition = headerMatch[1].trim();
				const innerCss = block.slice(block.indexOf('{') + 1, -1); // remove outermost {}

				const ruleRegex = /([^{]+)\{([^}]+)\}/g;
				let match;
				while ((match = ruleRegex.exec(innerCss))) {
					const selector = match[1].trim();
					const propsStr = match[2].trim();
					rules.push({
						selectors: selector,
						style: parseStyle(propsStr),
						atRule: 'media',
						params: mediaCondition,
					});
				}
			});

			// Step 2: Remove all media blocks from CSS to get top-level rules
			let cssWithoutMedia = cssString;
			mediaBlocks.forEach(block => {
				cssWithoutMedia = cssWithoutMedia.replace(block, '');
			});

			// Step 3: Parse top-level rules
			const ruleRegex = /([^{]+)\{([^}]+)\}/g;
			let match;
			while ((match = ruleRegex.exec(cssWithoutMedia))) {
				const selector = match[1].trim();
				const propsStr = match[2].trim();
				rules.push({
					selectors: selector,
					style: parseStyle(propsStr),
				});
			}

			return rules;
		}

        function showUploadLoader() {
          let existing = document.getElementById('gjs-upload-loader');
          if (existing) return;
        
          const loader = document.createElement('div');
          loader.id = 'gjs-upload-loader';
          loader.innerHTML = `<div class="gjs-loader-spinner">⏳ Uploading...</div>`;
          loader.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-size: 14px;
            z-index: 9999;
            border-radius: 5px;
          `;
          document.body.appendChild(loader);
        }
        
        function hideUploadLoader() {
          const loader = document.getElementById('gjs-upload-loader');
          if (loader) loader.remove();
        }
        
        const editor = grapesjs.init({
            noticeOnUnload: 0,
            container: "#gjs",
            height: "100vh",
            width: "100%",
            //plugins: ['grapesjs-style-border'],
            fromElement: true,
            avoidInlineStyle: true,
            showOffsets: false,
            storageManager: false,
            assetManager: {
              upload: yatcoData.upload_url,
              uploadName: 'file',
            
              uploadFile: (e) => {
                const files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                const formData = new FormData();
                const fileType = window._currentFileType || 'default';
                console.log(fileType);
                formData.append('file', files[0]);
                formData.append('trait_fileType', fileType);
            
                showUploadLoader();
            
                fetch(yatcoData.upload_url, {
                  method: 'POST',
                  body: formData,
                })
                  .then(res => res.json())
                  .then(result => {
                    hideUploadLoader();
            
                    if (result.success === false) {
                      const message = result.data?.message || 'Upload failed';
                      alert("❌ " + message);
                      return;
                    }
            
                    // ✅ Add to Asset Manager
                    const newAsset = result.data;
                    if (newAsset) {
                      editor.AssetManager.add(newAsset);
            
                      // ✅ Prepend to correct global icon list
                      const globalIconEntry = {
                        src: newAsset.src,
                        name: newAsset.name || 'New Icon',
                      };
            
                      if (fileType === 'svg') {
                        svg_icons.unshift(globalIconEntry);
                      } else if (fileType === 'png') {
                        png_icons.unshift(globalIconEntry);
                      }
            
                    }
                  })
                  .catch(err => {
                    hideUploadLoader();
                    console.error('Upload failed', err);
                    alert('❌ An unexpected error occurred.');
                  });
              }
            },            
            colorPicker: {
                appendTo: 'parent',
                showInput: true,
                preferredFormat: 'hex',
                allowEmpty: true, // Allow the color picker to have an empty value
                offset: { top: -90, left: -160,},
            },
            traitManager: {
                appendTo: '.traits-container',
                open: true,
              },
            layerManager: {
                appendTo: '.layers-container',
                open: true,
              },
            selectorManager: {
				appendTo: '.gjs-selectors-container', // 👈 container for selector input
                componentFirst: true,
            },
            parser: {
                parserCss: customCssParser
            },
            wrapper: {
                droppable: false,
                },
            panels: {
                defaults: [
                    {
                        id: "basic-actions",
                        el: ".panel__basic-actions",
                        buttons: [
                            {
                                id: "save",
                                className: "btn-save",
                                label: "Save",
                                command: "save-content",
                                attributes: { title: "Save" },
                            },
                            {
                                id: "desktop-view",
                                className: "btn-desktop",
                                label: "🖥 Desktop",
                                command: "set-device-desktop",
                                attributes: { title: "Desktop View" },
                            },
                            {
                                id: "tablet-view",
                                className: "btn-tablet",
                                label: "📱 Tablet",
                                command: "set-device-tablet",
                                attributes: { title: "Tab View" },
                            },
                            {
                                id: "mobile-view",
                                className: "btn-mobile",
                                label: "📱 Mobile",
                                command: "set-device-mobile",
                                attributes: { title: "Mobile View" },
                            },
                            {
                                id: "fullscreen",
                                className: "btn-fullscreen",
                                label: "⛶ Fullscreen",
                                command: "toggle-fullscreen",
                                attributes: { title: "Full Screen" },
                            },
                            /*{
                                id: "preview",
                                className: "btn-preview",
                                label: "👁",
                                command: "toggle-preview",
                                attributes: { title: "Preview" },
                            },*/
                            {
                                id: "show-layout",
                                className: "btn-show-layout",
                                label: '<i class="fa fa-columns"></i>',
                                command: "toggle-component-layout",
                                attributes: { title: "Component Layout" },
                            },
                            {
                                id: "fullWidth",
                                className: "full-width",
                                label: "⛶ Full Width",
                                command: "full-width",
                                attributes: { title: "Full Width"},
                            },
                            {
                                id: "undo",
                                className: "btn-undo",
                                label: "⎌ Undo",
                                command: "core:undo",
                                attributes: { title: "Undo" },
                            },
                            {
                                id: "redo",
                                className: "btn-redo",
                                label: "⎋ Redo",
                                command: "core:redo",
                                attributes: { title: "Redo" },
                            },
                            {
                                id: "preview-template",
                                className: "btn-preview-template",
                                label: '<i class="fa fa-eye"></i> Preview',
                                command: "update-template-preview",
                                attributes: { title: "Preview Template" },
                            },
                        ],
                    },
                    {
                        id: "options",
                        el: ".panel__options",
                        buttons: []
                    }
                ],
            },
            blockManager: {
                appendTo: "#blocks",
            },
            canvas: {
                styles: [
                  yatcoData.YATCO_PLUGIN_ASSETS+'build/bootstrap5/css/bootstrap.min.css',
                  yatcoData.YATCO_PLUGIN_ASSETS+'build/font-awesome/all.min.css',
                  yatcoData.YATCO_PLUGIN_ASSETS+'build/bootstrap5/font/bootstrap-icons.min.css'
                ],
                scripts: [
                  yatcoData.YATCO_PLUGIN_ASSETS+'build/bootstrap5/js/bootstrap.bundle.min.js'
                ]
            },
            cssComposer: {},
            styleManager: {
                appendTo: '.styles-container',  // Append styles here
                clearProperties: 1,
                sectors: [{
                    name: 'General',
                    open: false,
                    buildProps: ['outline', 'overflow', 'text-align', 'float', 'display', 'position', 'top', 'right', 'left', 'bottom', 'cursor'],
                    properties: [
                        {
                          name: 'Outline',
                          property: 'outline',
                          type: 'select',
                          defaults: '1px solid #ccc', // Set the default value to "Default"
                          list: [
                            { value: '1px solid #ccc', name: 'Default' },
                            { value: 'none', name: 'None' },
                          ]
                        }
                    ]
                  },
                  {
                    name: 'Flex',
                    open: false,
                    buildProps: ['flex-direction', 'flex-wrap', 'justify-content', 'align-items', 'align-content', 'order', 'flex-basis', 'flex-grow', 'flex-shrink', 'align-self'],
                  },
                  {
                    name: 'Dimension',
                    open: false,
                    buildProps: ['width', 'height', 'max-width', 'min-height', 'min-width', 'max-height', 'margin', 'padding', 'object-fit'],
					properties: [
                        {
                          name: 'Object-fit',
                          property: 'object-fit',
                          type: 'select',
                          defaults: 'cover', // Set the default value to "Default"
                          list: [
                            { value: 'cover', name: 'cover' },
                            { value: 'contain', name: 'contain' },
                            { value: 'none', name: 'none' },
                            { value: 'scale-down', name: 'scale-down' },
                            { value: 'inherit', name: 'inherit' },
                            { value: 'initial', name: 'initial' },
                            { value: 'fill', name: 'fill' },
                            { value: 'unset', name: 'unset' },
                          ]
                        }
                    ]
                  },
                  {
                    name: 'Typography',
                    open: false,
                    buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'stroke', 'text-transform', 'line-height', 'text-shadow'],
					properties: [
						{
						property: 'stroke',
						type: 'color', // This makes it a color picker
						defaults: '#000000',
						},
						// Optional: make 'color' picker too, if needed
						{
						property: 'color',
						type: 'color',
						defaults: '#000000',
						},
					]
				},
                  {
                    name: 'Decorations',
                    open: false,
                    buildProps: ['border-radius-c', 'background-color', 'box-shadow', 'border-radius', 'scale'],
                  },
                  { 
                    name: 'Border',
                    open: false,
                    buildProps : [ 'border', 'border-top', 'border-left', 'border-right', 'border-bottom' ],
                    properties: [
                        {
                          property: 'border-top',
                          type: 'composite',
                          properties: [
                            {
                              name: 'Width',
                              type: 'integer',
                              units: ['px', 'em', 'rem'],
                              property: 'border-top-width',
                            },
                            {
                              name: 'Style',
                              type: 'select',
                              property: 'border-top-style',
                              options: [
                                { value: 'none' },
                                { value: 'solid' },
                                { value: 'dotted' },
                                { value: 'dashed' },
                                { value: 'double' },
                                { value: 'groove' },
                                { value: 'ridge' },
                                { value: 'inset' },
                                { value: 'outset' }
                              ]
                            },
                            {
                              name: 'Color',
                              type: 'color',
                              property: 'border-top-color',
                            }
                          ]
                        },
                        {
                          property: 'border-right',
                          type: 'composite',
                          properties: [
                            {
                              name: 'Width',
                              type: 'integer',
                              units: ['px', 'em', 'rem'],
                              property: 'border-right-width',
                            },
                            {
                              name: 'Style',
                              type: 'select',
                              property: 'border-right-style',
                              options: [
                                { value: 'none' },
                                { value: 'solid' },
                                { value: 'dotted' },
                                { value: 'dashed' },
                                { value: 'double' },
                                { value: 'groove' },
                                { value: 'ridge' },
                                { value: 'inset' },
                                { value: 'outset' }
                              ]
                            },
                            {
                              name: 'Color',
                              type: 'color',
                              property: 'border-right-color',
                            }
                          ]
                        },
                        {
                          property: 'border-bottom',
                          type: 'composite',
                          properties: [
                            {
                              name: 'Width',
                              type: 'integer',
                              units: ['px', 'em', 'rem'],
                              property: 'border-bottom-width',
                            },
                            {
                              name: 'Style',
                              type: 'select',
                              property: 'border-bottom-style',
                              options: [
                                { value: 'none' },
                                { value: 'solid' },
                                { value: 'dotted' },
                                { value: 'dashed' },
                                { value: 'double' },
                                { value: 'groove' },
                                { value: 'ridge' },
                                { value: 'inset' },
                                { value: 'outset' }
                              ]
                            },
                            {
                              name: 'Color',
                              type: 'color',
                              property: 'border-bottom-color',
                            }
                          ]
                        },
                        {
                          property: 'border-left',
                          type: 'composite',
                          properties: [
                            {
                              name: 'Width',
                              type: 'integer',
                              units: ['px', 'em', 'rem'],
                              property: 'border-left-width',
                            },
                            {
                              name: 'Style',
                              type: 'select',
                              property: 'border-left-style',
                              options: [
                                { value: 'none' },
                                { value: 'solid' },
                                { value: 'dotted' },
                                { value: 'dashed' },
                                { value: 'double' },
                                { value: 'groove' },
                                { value: 'ridge' },
                                { value: 'inset' },
                                { value: 'outset' }
                              ]
                            },
                            {
                              name: 'Color',
                              type: 'color',
                              property: 'border-left-color',
                            }
                          ]
                        }
                    ]
                  },
                  { 
                    name: "Effects",
                    open: false,
                    buildProps: ["opacity", "filter", "mix-blend-mode"]
                  },
                  { 
                    name: "Transitions", 
                    open: false, 
                    buildProps: ["transition-property", "transition-duration", "transition-timing-function", "transition-delay"] },
                  { 
                    name: "Transforms", 
                    open: false, 
                    buildProps: ["transform", "transform-origin"] },
                  {
                    name: 'Extra',
                    open: false,
                    buildProps: ['transition', 'perspective', 'transform'],
                  }
                ],
            },
            deviceManager: {
                devices: [
                    { id: 'desktop', name: 'Desktop', width: '992px', widthMedia: '' }, // For large screens
                    { id: 'tablet', name: 'Tablet', width: '768px', widthMedia: '768px' }, // Large devices (e.g., laptops)
                    { id: 'mobile', name: 'Mobile', width: '576px', widthMedia: '767px' } // Small devices (mobile)
                ]
            }
        });
  
        editor.TraitManager.addType('icon-selector', {
          createInput({ trait }) {
            const container = document.createElement('div');
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'gjs-btn-prim gjs-btn--full';
            btn.innerText = trait.get('text') || 'Select Icon';
        
            btn.addEventListener('click', () => {
              const fileType = trait.get('fileType') || 'png';
              window._currentFileType = fileType;
        
              // ✅ Show SVG warning BEFORE opening asset manager
              if (fileType === 'svg' && !window.__svgWarningShown) {
                const confirmed = confirm(
                  '⚠️ SVG files may contain malicious scripts. Upload only trusted files.\n\nDo you want to continue?'
                );
        
                if (!confirmed) return;
        
                // Flag it so we don't show again
                window.__svgWarningShown = true;
        
                // Optional: Save confirmation in WordPress
                fetch(ajaxurl, {
                  method: 'POST',
                  credentials: 'same-origin',
                  headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                  },
                  body: 'action=yatco_confirm_svg_warning'
                });
              }
        
              // ✅ Continue as usual
              const am = editor.AssetManager;
              const icons = fileType === 'svg' ? svg_icons : png_icons;
        
              am.getAll().reset(icons);
              editor.AssetManager.open();
        
              const assetsContainer = document.querySelector('.gjs-am-assets');
              if (!assetsContainer) return;
        
              const assetClickHandler = function (e) {
                const assetEl = e.target.closest('.gjs-am-asset');
                if (!assetEl) return;
        
                const previewEl = assetEl.querySelector('.gjs-am-preview');
                if (!previewEl) return;
        
                const bgImage = previewEl.style.backgroundImage;
                const url = bgImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        
                if (fileType === 'svg') {
                  
                  const cachedSvg = window.__svgCache[url];
                  if (cachedSvg) {
                    trait.target?.set('content', cachedSvg);
                  } else {
                    // Fallback to fetch if not cached (should rarely happen)
                    fetch(url)
                      .then(res => res.text())
                      .then(svgText => {
                        if (svgText.includes('<svg')) {
                          trait.target?.set('content', svgText);
                          window.__svgCache[url] = svgText; // Cache it for next time
                        } else {
                          console.error('❌ Invalid SVG:', svgText);
                        }
                      });
                  }

                } else {
                  trait.target?.addAttributes({ src: url });
                }
        
                assetsContainer.removeEventListener('click', assetClickHandler);
                editor.AssetManager.close();
              };
        
              assetsContainer.addEventListener('click', assetClickHandler);
            });
        
            container.appendChild(btn);
            return container;
          }
        });

        editor.on('asset:remove', (asset) => {
            const assetUrl = asset.get('src');
            const assetName = assetUrl.split('/').pop();
            const fileType = assetUrl.includes('/png/') ? 'png' : assetUrl.includes('/svg/') ? 'svg' : 'unknown';
          
            // Simple native confirm
            const confirmed = window.confirm(`Are you sure you want to delete "${assetName}"?`);
            if (!confirmed) return;
          
            // Optional loader message
            const msg = document.createElement('div');
            msg.innerText = `⏳ Deleting "${assetName}"...`;
            msg.style.position = 'fixed';
            msg.style.top = '10px';
            msg.style.right = '10px';
            msg.style.background = '#f2f2f2';
            msg.style.padding = '8px 12px';
            msg.style.border = '1px solid #ccc';
            msg.style.borderRadius = '4px';
            document.body.appendChild(msg);
          
            fetch(yatcoData.remove_url, {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ url: assetUrl, fileType })
            })
              .then(res => res.json())
              .then(response => {
                msg.remove();
                if (response.success) {
                  editor.AssetManager.remove(asset); // ✅ clean and UI-safe
                } else {
                  alert(`❌ Could not delete "${assetName}": ${response.data?.message || 'Unknown error'}`);
                }
              })
              .catch(err => {
                msg.remove();
                alert(`❌ Error deleting "${assetName}"`);
                console.error(err);
              });
        });                    
          
        const injectCssRule = (editor, selectorStr, styleObj) => {
            const cssStr = `${selectorStr} { ${Object.entries(styleObj)
              .map(([key, val]) => `${key}: ${val};`)
              .join(' ')} }`;
          
            const frame = editor.Canvas.getFrameEl();
            const doc = frame?.contentDocument;
            const head = doc?.head;
          
            if (!head) {
              console.warn('❌ Unable to inject CSS: Canvas head not found');
              return;
            }
          
            // Find or create the wrapper div
            let wrapper = doc.getElementById('yt-builder-enqueue');
            if (!wrapper) {
              wrapper = doc.createElement('div');
              wrapper.id = 'yt-builder-enqueue';
              head.appendChild(wrapper);
            }
          
            // Check for existing style block with this selector inside the wrapper
            const existingStyles = wrapper.querySelectorAll('style');
            for (let styleTag of existingStyles) {
              if (styleTag.textContent.includes(selectorStr)) {
                styleTag.textContent = cssStr;
                return;
              }
            }
          
            // Otherwise, inject new <style> inside wrapper
            const styleTag = doc.createElement('style');
            styleTag.textContent = cssStr;
            wrapper.appendChild(styleTag);
        };

		editor.on('component:styleUpdate', (target) => {
			if (!target || !target.getAttributes) return;

			const attrs = target.getAttributes();
			const id = target.getId?.();
			const isSvgIcon = attrs?.class?.includes('svg-icon');
			if (!isSvgIcon || !id) return;

			const el = target.getEl();
			if (!el) return;

			const svg = el.querySelector('svg');
			if (!svg) return;

			const selector = `#${id} svg`;
			const style = target.getStyle();
			const shapeTags = ['path', 'rect', 'circle', 'ellipse', 'polygon', 'polyline'];

			// Parse and set width/height on <svg> tag directly
			const widthVal = style.width || '34px';
			const heightVal = style.height || '34px';
			svg.setAttribute('width', parseFloat(widthVal));
			svg.setAttribute('height', parseFloat(heightVal));

			// Inject width/height styles (always)
			injectCssRule(editor, selector, {
				width: widthVal,
				height: heightVal,
			});

			// Build fill/stroke rules
			const visualRules = {};
			if (style.color) visualRules.fill = style.color;
			if (style.stroke) visualRules.stroke = style.stroke;

			// Apply or remove fill/stroke styles
			shapeTags.forEach(tag => {
				const shapeSelector = `${selector} ${tag}`;

				if (Object.keys(visualRules).length > 0) {
					injectCssRule(editor, shapeSelector, visualRules);
				} else {
					injectCssRule(editor, shapeSelector, {
						fill: '',
						stroke: ''
					});
				}
			});

			// ✅ Remove temporary live style (once final styles applied)
			const canvasDoc = editor.Canvas.getDocument();
			const tempStyle = canvasDoc.getElementById('svg-live-style');
			if (tempStyle) {
				tempStyle.remove();
			}
		});

        editor.on('component:add', comp => {
			const attrs = comp.getAttributes?.() || {};
			const id = comp.getId?.();
			const isSvgIcon = attrs?.class?.includes('svg-icon');

			if (isSvgIcon && id) {
				setTimeout(() => {
					const style = comp.getStyle?.();
					const fill = style?.color;
					const stroke = style?.stroke;
					const rules = {};
					if (fill) rules.fill = fill;
					if (stroke) rules.stroke = stroke;

					if (Object.keys(rules).length === 0) return;

					const shapeTags = ['path', 'rect', 'circle', 'ellipse', 'polygon', 'polyline'];
					shapeTags.forEach(tag => {
						injectCssRule(editor, `#${id} svg ${tag}`, rules);
					});
				}, 100); // let style values populate
			}
		});

		editor.on('style:property:update', ({ property, value }) => {
			const propName = property?.attributes?.property;
			const target = editor.getSelected();

			if (!target || !target.getAttributes) return;

			const attrs = target.getAttributes();
			const id = target.getId?.();
			const isSvgIcon = attrs?.class?.includes('svg-icon');
			if (!isSvgIcon || !id) return;

			const el = target.getEl();
			if (!el) return;

			const svg = el.querySelector('svg');
			if (!svg) return;

			const selector = `#${id} svg`;
			const shapeTags = ['path', 'rect', 'circle', 'ellipse', 'polygon', 'polyline'];

			// Get canvas document
			const canvasDoc = editor.Canvas.getDocument();
			let styleTag = canvasDoc.getElementById('svg-live-style');
			if (!styleTag) {
				styleTag = canvasDoc.createElement('style');
				styleTag.id = 'svg-live-style';
				canvasDoc.head.appendChild(styleTag);
			}

			// Generate CSS
			let css = '';
			if (propName === 'color') {
				shapeTags.forEach(tag => {
					css += `${selector} ${tag} { fill: ${value} !important; }\n`;
				});
			}

			if (propName === 'stroke') {
				shapeTags.forEach(tag => {
					css += `${selector} ${tag} { stroke: ${value} !important; }\n`;
				});
			}

			styleTag.innerHTML = css;
		});

        // Define a constant with the default traits
        const textFields_defaultTraits = [
            {
            label: "Tag",
            name: "tagName",
            type: "select",
            options: [
                { value: "span", name: "span" },
                { value: "p", name: "p" },
                { value: "h1", name: "h1" },
                { value: "h2", name: "h2" },
                { value: "h3", name: "h3" },
                { value: "h4", name: "h4" },
                { value: "h5", name: "h5" }
            ],
            changeProp: 1,
            },
            {
            type: "text",
            label: "ID",
            name: "id",
            },
            {
            type: "text",
            label: "Title",
            name: "title"
            }
        ];
        
        const blockManager = editor.BlockManager;

        // Define a new component type called 'container'
        editor.DomComponents.addType('container', {
            model: {
            defaults: {
                tagName: 'div',
                attributes: { class: 'container gjs-component gjs-default-layout' },
                components: '<p>Container Content</p>',
                style: {
                    padding:'15px',
                },
                draggable: true,  // Explicitly allow dragging
                droppable: true   // Allow dropping children (if needed)
            }
            }
        });

        blockManager.add('container', {
            label: "Container",
            category: "Layout",
            content: { type: 'container' },
            attributes: { class: 'fa bi bi-box-fill' }
        });
        
        // Define a new component type called 'row'
        editor.DomComponents.addType('row', {
			model: {
			  defaults: {
				tagName: 'div',
				attributes: { class: 'row gjs-component gjs-default-layout' },
				components: '<p>Row Content</p>',
				style: {
				  padding: '15px'
				},
				draggable: true,
				droppable: true,
				traits: [
				  {
					type: 'checkbox',
					name: 'image_hover_effects',
					label: 'Image has effects?',
					valueTrue: 'true',
					valueFalse: 'false',
					changeProp: 1
				  }
				],
				image_hover_effects: 'false'
			  },
		  
			  init() {
				this.listenTo(this, 'change:image_hover_effects', this.toggleImageEffects);
			  },
		  
			  toggleImageEffects() {
				const enabled = this.get('image_hover_effects') === 'true';
				let classes = this.getClasses().filter(cls => cls !== 'image-has-hover-effects');
		  
				if (enabled) {
				  classes.push('image-has-hover-effects');
				}
		  
				this.setClass(classes);
			  }
			}
		});		  
        
        // Add a block that uses the new 'row' type
        blockManager.add('row', {
            label: "Row",
            content: { type: 'row' },
            category: "Layout",
            attributes: { class: 'fa bi bi-border-all' }
        });
  
       	// Define a new component type called 'column'
		editor.DomComponents.addType('column', {
			model: {
				defaults: {
					tagName: 'div',
					attributes: {
					class: 'col gjs-component gjs-default-layout'
					},
					components: '<p>Column Content</p>',
					style: {
					padding: '15px'
					},
					traits: [
					{
						type: 'select',
						label: 'Mobile Width',
						name: 'mobile_col_class',
						options: Array.from({ length: 12 }, (_, i) => {
						const val = `col-${i + 1}`;
						return { value: val, name: val };
						}),
						changeProp: 1
					},
					{
						type: 'select',
						label: 'Desktop Width',
						name: 'desktop_col_class',
						options: Array.from({ length: 12 }, (_, i) => {
						const val = `col-md-${i + 1}`;
						return { value: val, name: val };
						}),
						changeProp: 1
					},
					{
						type: 'checkbox',
						name: 'image_hover_effects',
						label: 'Image has effects?',
						valueTrue: 'true',
						valueFalse: 'false',
						changeProp: 1
					},
					{
						type: 'text',
						label: 'Max Height',
						name: 'max_height',
						placeholder: 'e.g. 200px',
						changeProp: 1
					}
					],
					mobile_col_class: '',
					desktop_col_class: '',
					image_hover_effects: 'false'
				},
			
				init() {
					this.listenTo(this, 'change:mobile_col_class change:desktop_col_class', this.updateColumnClasses);
					this.listenTo(this, 'change:image_hover_effects', this.toggleImageEffects);
					this.listenTo(this, 'change:max_height', this.updateMaxHeightAttr);
				},
			
				updateColumnClasses() {
					const mobileClass = this.get('mobile_col_class');
					const desktopClass = this.get('desktop_col_class');
			
					let classes = this.getClasses().filter(cls => !/^col(-md)?-\d+$/.test(cls));
			
					if (mobileClass) classes.push(mobileClass);
					if (desktopClass) classes.push(desktopClass);
			
					this.setClass(classes);
				},
			
				toggleImageEffects() {
					const enabled = this.get('image_hover_effects') === 'true';
					let classes = this.getClasses().filter(cls => cls !== 'image-has-hover-effects');
			
					if (enabled) {
					classes.push('image-has-hover-effects');
					}
			
					this.setClass(classes);
				},

			  updateMaxHeightAttr() {
					const val = this.get('max_height') || '';
					if (val) {
						this.addAttributes({ 'data-col-max-height': val });
					} else {
						this.removeAttributes('data-col-max-height');
					}
				}

			}
		});
  
        // Add a block that uses the new 'column' type
        blockManager.add('column', {
            label: "Column",
            content: { type: 'column' },
            category: "Layout",
            attributes: { class: 'fa bi bi-box2' }
        });
  
        // Define the custom two-column type
        editor.DomComponents.addType('two-column', {
            model: {
            defaults: {
                tagName: 'div',
                attributes: { class: 'row' },
                style: {
                padding: '10px 0',
                },
                components: [
                {
                    tagName: 'div',
                    type: 'column', // Using our previously defined column type
                    attributes: { class: 'col-md-8 col-sm-12 gjs-component gjs-default-layout' },
                    components: '<p>Column 1</p>',
                },
                {
                    tagName: 'div',
                    type: 'column', // Using our previously defined column type
                    attributes: { class: 'col-md-4 col-sm-12 gjs-component gjs-default-layout' },
                    components: '<p>Column 2</p>',
                },
                ],
            }
            }
        });
        
        // Now, add a block that uses the new two-column type
        blockManager.add('two-column', {
            label: "Two Columns",
            content: { type: 'two-column' },
            category: "Layout",
            attributes: { class: 'fa bi bi-layout-sidebar-inset-reverse' }
        });
  
        // Define the custom component type for a three-column layout
        editor.DomComponents.addType('three-column', {
            model: {
            defaults: {
                tagName: 'div',
                attributes: { class: 'row' },
                style: {
                padding: '10px 0'
                },
                components: [
                {
                    tagName: 'div',
                    type: 'column', // assuming a custom 'column' type is defined as shown earlier
                    attributes: { class: 'col-md-4 col-sm-12 gjs-component gjs-default-layout' },
                    components: '<p>Column 1</p>',
                },
                {
                    tagName: 'div',
                    type: 'column',
                    attributes: { class: 'col-md-4 col-sm-12 gjs-component gjs-default-layout' },
                    components: '<p>Column 2</p>',
                },
                {
                    tagName: 'div',
                    type: 'column',
                    attributes: { class: 'col-md-4 col-sm-12 gjs-component gjs-default-layout' },
                    components: '<p>Column 3</p>',
                },
                ],
            }
            }
        });
        
        // Add a block that uses the new 'three-column' type
        blockManager.add('three-column', {
            label: "Three Columns",
            content: { type: 'three-column' },
            category: "Layout",
            attributes: { class: 'fa bi bi-layout-three-columns' }
        });
  
        // Define the grid column component type
        editor.DomComponents.addType('grid-col', {
            model: {
            defaults: {
                tagName: 'div',
                attributes: { class: 'grid-item gjs-component gjs-default-layout' },
                droppable: true,
                components: '<p>Grid Item</p>',
                style: {
                border: '1px solid #ccc',
                padding: '10px'
                }
            }
            }
        });
        
        // Define the grid container component type
        editor.DomComponents.addType('grid-container', {
            model: {
            defaults: {
                tagName: 'div',
                attributes: { class: 'grid-container gjs-default-layout' },
                style: {
                display: 'grid',
                'grid-template-columns': 'repeat(auto-fit, minmax(200px, 1fr))',
                gap: '10px'
                },
                droppable: true, // Allow dropping of grid columns or other components
                // Pre-create two grid columns as initial content (users can add more later)
                components: [
                { type: 'grid-col', components: 'Column 1' },
                { type: 'grid-col', components: 'Column 2' }
                ]
            }
            }
        });
        
        // Add a block for the grid container (which includes grid columns)
        blockManager.add('grid', {
            label: "Grid",
            category: "Layout",
            content: { type: 'grid-container' },
            attributes: { class: 'fa bi bi-grid' } // Using a Font Awesome icon for grid
        });
  
        // Define a custom component type for a card
        editor.DomComponents.addType('card', {
			model: {
			  	defaults: {
					tagName: 'div',
					attributes: { class: 'card gjs-component gjs-default-layout' },
					style: {
					'max-width': '100%',
					padding: '10px',
					'border-top': '1px solid #cccccc',
					'border-right': '1px solid #cccccc',
					'border-bottom': '1px solid #cccccc',
					'border-left': '1px solid #cccccc',
					'min-height': '30px',
					background: '#ffffff'
					},
					components: '<p>Card Content</p>',
					traits: [
					{
						type: 'checkbox',
						name: 'image-hover-effects',
						label: 'Image has effects?',
						valueTrue: 'true',
						valueFalse: 'false',
					}
					]
				},
			
				init() {
				this.on('change:attributes:image-hover-effects', () => {
					const val = this.getAttributes()['image-hover-effects'];
					const hasClass = this.getClasses().includes('image-has-hover-effects');
			
					if (val === 'true' && !hasClass) {
					this.addClass('image-has-hover-effects');
					} else if (val !== 'true' && hasClass) {
					this.removeClass('image-has-hover-effects');
					}
				});
				}
			}
		});
		
		// Block registration
		blockManager.add('card', {
		label: "Card",
		content: { type: 'card' },
		category: "Layout",
		attributes: { class: 'fa bi bi-fullscreen' }
		});		  
        
        editor.DomComponents.addType('sticky-div', {
            model: {
              defaults: {
                tagName: 'div',
                classes: ['sticky-div',  'gjs-component', 'gjs-default-layout'],
                components: [
                  {
                    tagName: 'p',
                    content: 'I am a sticky div',
                    // Ensure the p tag is removable and editable
                    removable: true,
                    editable: true
                  }
                ],
                // Default styles
                style: {
                    'position': 'sticky',
                    'top': '0px',
                    'zIndex': '1000',
                    'background': '#fff',
                    padding:'15px'
                },
              },
            },
        });
        
        blockManager.add('sticky-div-block', {
            label: 'Sticky Div',
            category: 'Layout',
            content: { type: 'sticky-div' },
            attributes: { class: 'fa bi bi-sticky' }
        });
      
        // ✅ Basic Elements
        blockManager.add("text", {
            label: "Text",
            content: {
                tagName: "span",
                type: "text",
                components: "Drag me!", // Add text content properly
                attributes: {}, // No inline styles here
                // Add a trait for choosing the HTML tag, id, class, title
                traits: textFields_defaultTraits,
            },
            category: "Basic",
            attributes: { class: 'fa fab fa-adn' }
        });

		editor.DomComponents.addType("text", {
			model: {
			  defaults: {
				tagName: "span",
				traits: textFields_defaultTraits,
				attributes: {},
			  },
			}
		});

		blockManager.add('Link', {
			label: 'Link',
			content: {
				type: 'Link'
			},
			category: 'Basic',
			attributes: { class: 'fa fas fa-at' }
		});

        editor.DomComponents.addType('Link', {
			model: {
				defaults: {
				tagName: 'a',
				attributes: {
					href: '#',
					target: '_self'
				},
				droppable: true,
				components: '<p>Click or drop content here</p>',
				style: {
					padding: '15px',
					display: 'block'
				},
				traits: [
					{
					label: 'Link URL',
					name: 'href',
					type: 'text',
					placeholder: '{a_url}'
					},
					{
					label: 'Open in New Tab',
					name: 'target',
					type: 'select',
					options: [
						{ value: '_self', name: 'Same Tab' },
						{ value: '_blank', name: 'New Tab' }
					],
					default: '_self'
					},
					{
					type: 'checkbox',
					label: 'Read More Text',
					name: 'readMoreToggle',
					valueTrue: 'true',
					valueFalse: 'false'
					},
					{
					type: 'text',
					label: 'Toggle Text',
					name: 'toggleText',
					placeholder: 'e.g. Read More',
					changeProp: 1
					}
				]
				},

				init() {
				this.on('change:attributes:readMoreToggle', () => {
					const val = this.getAttributes()['readMoreToggle'];
					if (val === 'true') {
					this.addClass('yt-read-more-button');
					} else {
					this.removeClass('yt-read-more-button');
					}
				});
				this.listenTo(this, 'change:toggleText', this.updateToggleText);
				},
				updateToggleText() {
					const val = this.get('toggleText') || '';
					if (val) {
						this.addAttributes({ 'data-toggle-text': val });
					} else {
						this.removeAttributes('data-toggle-text');
					}
				}
			},
		});


        //buttons lets keep in one place as it needs separate onclick functions for vessel leads and charter contact

        // Define a custom button component type with an editable label
        editor.DomComponents.addType('yacht-charter-button', {
			model: {
			defaults: {
				tagName: 'button',
				attributes: {
				onclick: "openCharterLeadModal('{VesselID}', '{CompanyID}')",
				class: 'btn btn-primary'
				},
				// Default inner text (visible label)
				components: 'Contact',
				// Custom property to hold the label value (default is "Contact")
				label: 'Contact',
				// Define traits: one for the label and one for the link URL
				traits: [
					{
						label: 'Label',
						name: 'label',
						type: 'text',
						changeProp: 1, // Listen to changes so we can update the component
						placeholder: 'Contact'
					},
					{
						label: 'On Click',
						name: 'onclick',
						type: 'text',
						placeholder: "alert('Clicked!')"
					}
				]
			},
			init() {
				// When the 'label' property changes, update the button's inner content
				this.on('change:label', function(model) {
				model.components(model.get('label'));
				});
			}
			},
			view: {}
		});

		blockManager.add("button", {
			label: "Charter Contact Button",
			content: {
				type: 'yacht-charter-button'
			},
			category: "Basic",
			attributes: { class: 'fa fa-hand-pointer' }
		});

		// Define a custom button component type with an editable label
		editor.DomComponents.addType('yacht-sale-button', {
			model: {
			defaults: {
				tagName: 'button',
				attributes: {
				onclick: "openContactModal('{MLSID}', '{CompanyID}', '{BrokerList:CompanyName}', '{BrokerList:BrokerName}')",
				class: 'btn btn-primary'
				},
				// Default inner text (visible label)
				components: 'Contact',
				// Custom property to hold the label value (default is "Contact")
				label: 'Contact',
				// Define traits: one for the label and one for the link URL
				traits: [
					{
						label: 'Label',
						name: 'label',
						type: 'text',
						changeProp: 1, // Listen to changes so we can update the component
						placeholder: 'Contact'
					},
					{
						label: 'On Click',
						name: 'onclick',
						type: 'text',
						placeholder: "alert('Clicked!')"
					}
				]
			},
			init() {
				// When the 'label' property changes, update the button's inner content
				this.on('change:label', function(model) {
				model.components(model.get('label'));
				});
			}
			},
			view: {}
		});

		// Create a block that uses the custom button component
		blockManager.add('contact-button', {
			label: 'Vessel Leads Button',
			content: {
				type: 'yacht-sale-button'
			},
			category: 'Basic',
			attributes: { class: 'fa fa-hand-pointer' }
		});

		blockManager.add('image-block', {
			label: 'Image',
			category: 'Basic',
			attributes: { class: 'fa fa-image' },
			content: {
				type: 'image-block',
				tagName: 'div',
				attributes: {
					class: 'gjs-image-wrapper with-overlay',
					'data-type': 'image-block',
					'with-overlay': 'true',
				},
				style: {
					width: '100%',
					position: 'relative',
					display: 'inline-block',
				},
				components: [
					{
					tagName: 'img',
					attributes: {
						src: yatcoData.placeholder_img,
						class: 'gjs-image',
					},
					style: {
						width: '100%',
					},
					},
					{
					tagName: 'div',
					attributes: {
						class: 'image-overlay',
					},
					style: {
						position: 'absolute',
						top: '0',
						left: '0',
						color: 'white',
						width: '100%',
						height: '100%',
						'background-color': 'rgba(0, 0, 0, 0.5)',
						'justify-content': 'center',
						'align-items': 'center',
						'font-size': '1.2em',
						'text-align': 'center',
					},
					components: [
						{
						tagName: 'span',
						type: 'text', // must be 'text' for editable behavior
						attributes: { class: 'overlay-span' },
						content: '{VesselName}',
						editable: true,
						}
					]
					}
				],
			},
		});
		
		editor.DomComponents.addType('image-block', {
			model: {
				defaults: {
					traits: [
					{
						type: 'checkbox',
						label: 'Show Overlay',
						name: 'with-overlay',
						valueTrue: 'true',
						valueFalse: 'false',
					},
					],
					styles: `
					.image-overlay {
						display: none;
					}
					.gjs-image-wrapper.with-overlay:hover .image-overlay {
						display: flex;
					}
					`,
				},
			
				init() {
					this.on('change:attributes:with-overlay', () => {
					const val = this.getAttributes()['with-overlay'];
					if (val === 'true') {
						this.addClass('with-overlay');
					} else {
						this.removeClass('with-overlay');
					}
					});
				},
			}
		});		  
		
		// Define an icon component
		editor.DomComponents.addType("icon", {
			model: {
				defaults: {
					tagName: "i",
					attributes: { class: "fa fa-star" }, // Default icon
					resizable: true,
					draggable: true,
					droppable: false,
					style: {
						"width": "30px",
						"height": "30px",
						"display": "inline-block",
						"font-size": "30px",
						"text-align": "center",
						"line-height": "30px",
					},
				},
			},
		});

		// Add an icon block in the Block Manager
		blockManager.add("icon", {
			label: "Icon",
			content: {
				type: "icon",
			},
			category: "Icons",
			attributes: { class: "fa fa-star" },
		});

		/* Bootstrap icons starts here*/
		editor.DomComponents.addType("b-icon", {
			model: {
			defaults: {
				tagName: "i",
				attributes: { class: "bi bi-bootstrap-fill" }, // Default icon
				resizable: true,
				draggable: true,
				droppable: false,
				style: {
				width: "30px",
				height: "30px",
				display: "inline-block",
				"font-size": "30px",
				"text-align": "center",
				"line-height": "30px",
				},
			},
			},
		});

		blockManager.add("b-icon", {
			label: "B-Icon",
			content: {
			type: "b-icon",
			},
			category: "Icons",          
			attributes: { class: "fa bi bi-bootstrap-fill" },
		});

		window.selectBootstrapIcon = function (targetEl) {
			if (document.getElementById("bi-backdrop")) return;
		
			// Create the backdrop
			const backdrop = document.createElement("div");
			backdrop.id = "bi-backdrop";
			backdrop.style.cssText = `
			position: fixed;
			top: 0; left: 0;
			width: 100vw;
			height: 100vh;
			background: rgba(0, 0, 0, 0.2);
			z-index: 9999;
			display: flex;
			align-items: center;
			justify-content: center;
			`;
		
			// Create the modal
			const modal = document.createElement("div");
			modal.id = "bi-modal";
			modal.style.cssText = `
			width: 600px;
			max-height: 80vh;
			overflow-y: auto;
			background: #fff;
			border: 1px solid #ccc;
			padding: 1rem;
			z-index: 10000;
			box-shadow: 0 5px 15px rgba(0,0,0,0.3);
			font-family: sans-serif;
			position: relative;
			`;
		
			modal.innerHTML = `
			<input type="text" id="bi-search" placeholder="Search icons..." style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;" />
			<div id="bi-list" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
			`;
		
			backdrop.appendChild(modal);
			document.body.appendChild(backdrop);
		
			// Close when clicking outside the modal
			backdrop.addEventListener("click", (e) => {
			if (e.target === backdrop) backdrop.remove();
			});
		
			// Load icons
			fetch(`${yatcoData.YATCO_PLUGIN_ASSETS}build/bootstrap5/font/bootstrap-icons.json`)
			.then(res => res.json())
			.then(iconMap => {
				const iconNames = Object.keys(iconMap);
				const list = modal.querySelector("#bi-list");
				const search = modal.querySelector("#bi-search");
		
				const renderIcons = (filter = "") => {
				list.innerHTML = "";
				iconNames
					.filter(name => name.includes(filter))
					.slice(0, 500)
					.forEach(name => {
					const btn = document.createElement("button");
					btn.innerHTML = `<i class="bi bi-${name}"></i>`;
					btn.title = name;
					btn.style.cssText = `
						font-size: 24px;
						border: 1px solid #eee;
						background: #fff;
						padding: 10px;
						cursor: pointer;
						width: 48px;
						height: 48px;
						text-align: center;
					`;
		
					btn.onclick = () => {
						// ✅ Update model instead of only DOM
						const comp = editor.getSelected();
						if (comp && comp.get('type') === 'b-icon') {
						comp.addAttributes({ class: `bi bi-${name}` });
						}
						backdrop.remove();
					};
		
					list.appendChild(btn);
					});
				};
		
				renderIcons();
		
				search.addEventListener("input", () => {
				renderIcons(search.value.trim().toLowerCase());
				});
			});
		};
      	/* Bootstrap icons ends here*/
      
		// Define a png icon component type for icons using the asset-selector trait
		editor.DomComponents.addType('png-icon', {
			isComponent: el =>
			el.tagName === 'IMG' && el.classList.contains('custom-icon'),
			model: {
			defaults: {
				tagName: 'img',
				attributes: {
				src: yatcoData.YATCO_PLUGIN_ASSETS+'install-assets/icons/png/phone-message-256.png',
				alt: 'Png Icon',
				class: 'png-icon'
				},
				traits: [
				{
					type: 'icon-selector',
					label: 'Upload PNG',
					fileType: 'png',
					name: 'icon-png'
				},
				],
				style: {
				width: '64px',
				height: '64px',
				objectFit: 'contain'
				}
			},
			init() {
				this.on('change:attributes', () => {
				const attrs = this.get('attributes');
				if (attrs.src) {
					this.view.el.src = attrs.src;
				}
				});
			}
			}
		});
      
		// Add the block so users can drag the custom icon component onto the canvas
		blockManager.add('png-icon', {
			label: 'PNG Icon',
			category: 'Icons',
			content: { type: 'png-icon' },
			attributes: { class: 'fa fa-image' }
		});
        
		// Define a svg component type for inline SVG icons wrapped in a span
		editor.DomComponents.addType('svg-icon', {
			isComponent: el => el.tagName === 'SPAN' && el.classList.contains('svg-icon'),
		
			model: {
			defaults: {
				tagName: 'span',
				content: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.94 47.94" fill="currentColor" xml:space="preserve" version="1.1"><path d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757 c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042 c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685 c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528 c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956 C22.602,0.567,25.338,0.567,26.285,2.486z"></path></svg>',
				attributes: {
				class: 'svg-icon'
				},
				traits: [
				{
					type: 'icon-selector',
					label: 'Upload SVG',
					fileType: 'svg',
					name: 'icon-svg'
				}
				],
				style: {
				display: 'inline-block',
				width: '64px',
				height: '64px'
				}
			},
			init() {
				this.on('change:content', this.updateSVG, this);
			},
			updateSVG() {
				const view = this.view;
				if (view && view.el) {
				view.el.innerHTML = this.get('content');
				}
			}
			},
			view: {
			onRender() {
				this.model.updateSVG();
			}
			}
		});

		// Register the block in the Block Manager
		blockManager.add('svg-icon', {
			label: 'SVG Icon',
			category: 'Icons',
			content: { type: 'svg-icon' },
			attributes: { class: 'fa fa-image' }
		});

		// Define an variable component
		editor.DomComponents.addType('variable', {
			model: {
				defaults: {
					tagName: "span",
					traits: textFields_defaultTraits,
					attributes: { class: `api-variable` }, // ✅ Unique class for each
					content: "{variable}", // ✅ Default placeholder format
					draggable: true,
					droppable: false
				},
			},
			view: {
				init() {
					this.listenTo(this.model, "change:attributes", this.render);
				},
			},
		});

		blockManager.add("variable", {
			label: "Variable",
			content: {
				type: "variable",
				traits: textFields_defaultTraits,
			},
			category: "Dynamic",
			attributes: {
				class: "fa bi bi-braces-asterisk",
				"data-variable": "",
			},
		});		
        
		// Define a new 'slider' component type
		editor.DomComponents.addType('slider', {
			model: {
			defaults: {
				tagName: 'div',
				draggable: true,
				droppable: true,
				attributes: { class: 'slider-block' },
				// Default content for the slider – an image placeholder.
				components: [
				{
					tagName: 'img',
					attributes: {
					src: yatcoData.placeholder_slider, // Ensure yatcoData.placeholder_slider is defined with your image URL
					alt: 'Slider Placeholder',
					class: 'slider-placeholder-image',
					},
					style: {
					width: '100%',
					height: 'auto',
					'max-width': '100%',
					},
				},
				],
				// Additional slider-specific properties or traits can be added here
			},
			},
			view: {
			// Optional: Custom view logic for slider interactions can be implemented here
			},
		});

		blockManager.add('slider', {
			label: "Slider",
			category: "Dynamic",
			// Instead of redefining the content, we simply reference the 'slider' type
			content: { type: 'slider' },
			attributes: { class: "fa fa-images" }
		});

		// Define a new 'slider2' component type
		editor.DomComponents.addType('slider2', {
			model: {
			defaults: {
				tagName: 'div',
				draggable: true,
				droppable: true,
				attributes: { class: 'slider2-block' },
				// Default content for the slider – an image placeholder.
				components: [
				{
					tagName: 'img',
					attributes: {
					src: yatcoData.placeholder_slider2, // Ensure yatcoData.placeholder_slider is defined with your image URL
					alt: 'Slider 2 Placeholder',
					class: 'slider2-placeholder-image',
					},
					style: {
					width: '100%',
					height: 'auto',
					'max-width': '100%',
					},
				},
				],
				// Additional slider-specific properties or traits can be added here
			},
			},
			view: {
			// Optional: Custom view logic for slider interactions can be implemented here
			},
		});

		blockManager.add('slider2', {
			label: "Slider 2",
			category: "Dynamic",
			// Instead of redefining the content, we simply reference the 'slider2' type
			content: { type: 'slider2' },
			attributes: { class: "fa fa-images" }
		});
      
		// Define a new 'gallery' component type
		editor.DomComponents.addType('gallery', {
			model: {
			defaults: {
				tagName: 'div',
				draggable: true,
				droppable: true,
				attributes: { class: 'gallery-block' },
				// Default content for the gallery – a placeholder image.
				components: [
				{
					tagName: 'img',
					attributes: {
					src: yatcoData.placeholder_gallery, // Make sure yatcoData.placeholder_gallery contains your image URL
					alt: 'Gallery Placeholder',
					class: 'gallery-placeholder-image',
					},
					style: {
					width: '100%',
					height: 'auto',
					'max-width': '100%',
					},
				},
				],
				// Additional gallery-specific properties or traits can be added here
			},
			},
			view: {
			// Optional: Add custom view logic for gallery interactions if needed
			},
		});

		blockManager.add('gallery', {
			label: "Gallery",
			category: "Dynamic",
			// Reference the custom 'gallery' type defined above
			content: { type: 'gallery' },
			attributes: { class: "fa bi bi-images" }
		});

		editor.DomComponents.addType('video-block', {
			model: {
				defaults: {
				tagName: 'video',
				attributes: {
					class: 'video-block yt-default-video-placeholder',
					poster: yatcoData.placeholder_video, // Placeholder image
					controls: true,
				},
				components: '', // video should not have children
				style: {
					width: '100%',
					height: 'auto',
					'max-width': '100%',
				},
				draggable: true,
				droppable: false,
				// Optional: add traits if you want dynamic video src input
				}
			},
			view: {
				// Optional: You can enhance this later with preview buttons, etc.
			}
		});
        
		blockManager.add('video-block', {
			label: 'Video',
			category: 'Dynamic',
			content: {
				type: 'video-block',
				attributes: {
				src: '', // Optional: can be set via trait later
				}
			},
			attributes: { class: 'fa bi bi-camera-video' }
		});          

		// Define a custom component type for a contact form
		editor.DomComponents.addType('contact-form', {
			model: {
			defaults: {
				tagName: 'div',
				draggable: true,
				attributes: { class: 'contact-form' },
				components: [
				{
					tagName: 'img',
					attributes: {
					src: yatcoData.placeholder_contactForm,
					alt: 'Contact Placeholder',
					class: 'contactForm-placeholder-image',
					},
					style: {
					width: '100%',
					height: 'auto',
					'max-width': '100%',
					},
				},
				],
			}
			}
		});
      
		// Add a block that uses the custom 'contact-form' type
		blockManager.add('contactForm', {
			label: "Contact Form",
			content: { type: 'contact-form' },
			category: "Dynamic",
			attributes: { class: "fa fa-solid fa-envelope" }
		});

		//Full Specification Accordion Block Starts
		// 1. Register the Block properly (no nested raw HTML, only the wrapper)
		editor.BlockManager.add('full-specs-accordion', {
			label: 'Full Specs',
			category: 'Basic',
			content: {
				type: 'accordion-block'
			},
			attributes: { class: 'fa bi bi-collection' }
		});

		// 2. Accordion Block Component (injects one item)
		editor.DomComponents.addType('accordion-block', {
		isComponent: el => el.classList?.contains('accordion-block'),
			model: {
				defaults: {
				name: 'Full Specs',
				draggable: true,
				classes: ['accordion-block'],
				components: [
					{
					type: 'accordion-item'
					}
				]
				}
			}
		});

		// 3. Accordion Item Component (with editable & stylable sections)
		editor.DomComponents.addType('accordion-item', {
			isComponent: el => el.classList?.contains('accordion-item'),
			model: {
				defaults: {
				name: 'Accordion Item',
				draggable: false,
				droppable: false,
				classes: ['accordion-item'],
				components: [
					{
					tagName: 'div',
					classes: ['accordion-title'],
					content: 'Spec Title',
					editable: true,
					style: {
						'font-weight': 'bold',
						'padding': '10px',
						'background-color': '#f1f1f1',
						'border-bottom': '1px solid #ddd',
						'font-size': '16px'
					},
					},
					{
					tagName: 'div',
					classes: ['accordion-content'],
					components: [
						{
						tagName: 'div',
						classes: ['accordion-inner'],
						content: 'Spec content goes here.',
						editable: true,
						style: {
							'padding': '10px',
							'border': '1px solid #ddd',
							'border-top': 'none',
							'font-size': '14px',
							'line-height': '1.5'
						},
						}
					],
					}
				]
				}
			}
		});

		// Full Specification Accordion Block Ends

		//Only for charter
		
		//Amenities block starts here
		window.yachtAmenities = yatcoData.Amenities;
		// ✅ Add Amenities Block to GrapesJS
		const defaultAmenityGroups = [
			'EXTERIOR FEATURES',
			'HELICOPTER / SEAPLANE',
			'FITNESS AND WELLNESS',
			'SPECIAL ACCESS'
		];
		  
		blockManager.add('amenities-block', {
			label: 'Amenities',
			category: 'Charter',
			attributes: { class: 'fa bi bi-check-square' },
			content: {
			  type: 'amenities-block',
			  tagName: 'div',
			  attributes: {
				'data-type': 'amenities-block',
				class: 'yt-amenities-wrapper'
			  },
			  components: [
				{
					tagName: 'div',
					attributes: {
						class: 'row',
						'data-separator': ', ',
						'data-selected-groups': defaultAmenityGroups.join('|')
					},
					components: (window.yachtAmenities.Groups || []).map(group => ({
						tagName: 'div',
						attributes: {
						class: 'col-md-6 amenity-items p-3', // ✅ updated here
						'data-group-name': group.AmenityGroupName
						},
						components: [
						{
							tagName: 'h4',
							attributes: { class: 'amenity-group-title' },
							content: group.AmenityGroupName
						},
						{
							tagName: 'div',
							attributes: { class: 'amenity-list' },
							content: group.Items.map(i => i.AmenityName).join(', ')
						}
						]
					}))
				}
			  ]
			}
		});
		
		// ✅ Register the component type
		editor.DomComponents.addType('amenities-block', {
			model: {
				defaults: {
					tagName: 'div',
					attributes: { 'data-type': 'amenities-block' },
					draggable: true,
					droppable: false,
					traits: [
					{
						type: 'text',
						label: 'Item Separator',
						name: 'data-separator',
						value: ', ',
						changeProp: true,
						tooltip: 'Separator between amenity items'
					}
					]
				},
				init() {
					this.on('change:data-separator', () => {
						const wrapper = this.components().at(0); // .row
						const separator = this.get('data-separator') || ', ';
						const groups = wrapper?.components() || [];
				
						groups.forEach(group => {
							const groupName = group.getAttributes()['data-group-name'];
							const match = window.yachtAmenities.Groups.find(g => g.AmenityGroupName === groupName);
							if (match) {
							const list = group.find('.amenity-list')[0];
							if (list) {
								list.components(match.Items.map(i => i.AmenityName).join(separator));
							}
							}
						});

						wrapper.addAttributes({
							'data-separator': this.get('data-separator')
						});
					});
				}
			},
			view: {
				init() {
					this.listenTo(this.model, 'change:attributes', this.render);
				}
			}
		});					
		
		editor.on("component:selected", (model) => {
			if (model.get("type") === "amenities-block") {
			  const toolbar = model.get("toolbar") || [];
			  if (!toolbar.some(btn => btn.command === "edit-amenities")) {
				toolbar.unshift({
				  id: "edit-amenities",
				  command: "edit-amenities",
				  label: "✏️",
				});
				model.set({ toolbar });
			  }
			}
		});

		// ✅ Define the edit command
		editor.Commands.add('edit-amenities', {
			run(editor) {
			  const selected = editor.getSelected();
			  if (!selected || selected.get('type') !== 'amenities-block') return;
		  
			  const wrapper = selected.components().at(0); // The .row container
			  const amenityData = window.yachtAmenities?.Groups || [];
			  const prev = wrapper.getAttributes()['data-selected-groups'];
			  const previouslySelected = prev ? prev.split('|') : [];
		  
			  const modalContent = `
				<form id="amenities-options-form">
				  ${amenityData.map(group => {
					const checked = previouslySelected.includes(group.AmenityGroupName) || defaultAmenityGroups.includes(group.AmenityGroupName);
					return `
					  <div>
						<label>
						  <input type="checkbox" name="group" value="${group.AmenityGroupName}" ${checked ? 'checked' : ''} />
						  ${group.AmenityGroupName}
						</label>
					  </div>
					`;
				  }).join('')}
				  <button type="submit" class="btn btn-primary mt-2">Update</button>
				</form>
			  `;
		  
			  editor.Modal.open({
				title: 'Configure Amenity Groups',
				content: modalContent
			  });
		  
			  document.getElementById('amenities-options-form').addEventListener('submit', (e) => {
				e.preventDefault();
				const form = e.target;
				const selectedGroups = [...form.querySelectorAll('input[name="group"]:checked')].map(i => i.value);
		  
				const components = selectedGroups.map(groupName => {
				  const group = amenityData.find(g => g.AmenityGroupName === groupName);
				  if (!group) return null;
		  
				  return {
					tagName: 'div',
					attributes: {
						class: 'col-md-6 amenity-items p-3',
						'data-group-name': groupName
					},
					components: [
					  {
						tagName: 'h4',
						attributes: { class: 'amenity-group-title' },
						content: groupName,
					  },
					  {
						tagName: 'div',
						attributes: { class: 'amenity-list' },
						content: group.Items.map(item => item.AmenityName).join(', ') // You can change separator via traits
					  }
					]
				  };
				}).filter(Boolean);
		  
				wrapper.components().reset(components);
				wrapper.addAttributes({ 'data-selected-groups': selectedGroups.join('|') });
				editor.Modal.close();
			  });
			}
		});				
		//Amenities block function ends here
		
		//Staterooms block starts here
		// ✅ Inject PHP Staterooms array into JS
		window.yachtStaterooms = yatcoData.Staterooms || [];

		blockManager.add('stateroom-block', {
			label: 'Staterooms',
			category: 'Charter',
			attributes: { class: 'fa bi bi-building' },
			content: {
				type: 'stateroom-block',
				tagName: 'div',
				attributes: {
				'data-type': 'stateroom-block',
				class: 'yt-staterooms-wrapper'
				},
				components: [{
					tagName: 'div',
					attributes: {
						class: 'row',
						'data-selected-fields': 'AccTypeName,BedTypeName,TTLGuest',
						'data-item-count':'10',
						'data-button-text':'Load More'
					},
					components: (window.yachtStaterooms || []).map((room, index) => ({
					tagName: 'div',
					attributes: {
						class: `col-md-6 stateroom-item p-3${index > 0 ? ' grapesjs-dummy-block' : ''}`
					},
					components: [{
						tagName: 'div',
						attributes: { class: 'stateroom-field yt-acctypename' },
						components: [
							{ tagName: 'span', attributes: { class: 'staterooms-field-label' }, content: 'Cabin Name: ' },
							{ tagName: 'span', attributes: { class: 'staterooms-field-value' }, content: room.AccTypeName || '' }
						]
						},
						{
						tagName: 'div',
						attributes: { class: 'stateroom-field yt-bedtypename' },
						components: [
							{ tagName: 'span', attributes: { class: 'staterooms-field-label' }, content: 'Bed: ' },
							{ tagName: 'span', attributes: { class: 'staterooms-field-value' }, content: room.BedTypeName || '' }
						]
						},
						{
						tagName: 'div',
						attributes: { class: 'stateroom-field yt-ttlguest' },
						components: [
							{ tagName: 'span', attributes: { class: 'staterooms-field-label' }, content: 'Guests: ' },
							{ tagName: 'span', attributes: { class: 'staterooms-field-value' }, content: room.TTLGuest || '' }
						]
						}]
					}))
				}]
			}
		});

		editor.DomComponents.addType('stateroom-block', {
			model: {
				defaults: {
					tagName: 'div',
					attributes: { 'data-type': 'stateroom-block' },
					draggable: true,
					droppable: false,
					traits: [
						{
						  type: 'number',
						  label: 'No. of Items',
						  name: 'data-item-count',
						  value: '10',
						  placeholder: 'e.g., 2',
						  changeProp: true,
						  min: 1,
						  default: 1,
						  tooltip: 'Used to limit how many items to show initially.',
						},
						{
						  type: 'text',
						  label: 'Button Text',
						  name: 'data-button-text',
						  placeholder: 'Load More',
						  changeProp: true,
						  default: 'Load More',
						  tooltip: 'Text shown on the load more button.',
						}
					]
				},
				init() {
					this.on('change:data-item-count change:data-button-text', () => {
					  const row = this.components().at(0); // 🔍 get .row wrapper
					  if (row && row.getAttributes()['data-selected-fields']) {
						row.addAttributes({
						  'data-item-count': this.get('data-item-count'),
						  'data-button-text': this.get('data-button-text'),
						});
					  }
					});
				}
			},
			view: {
				init() {
					this.listenTo(this.model, 'change:attributes', this.render);
				}
			}
		});

		editor.on("component:selected", (model) => {
			if (model.get("type") === "stateroom-block") {
				const toolbar = model.get("toolbar") || [];
				if (!toolbar.some(btn => btn.command === "edit-stateroom")) {
				toolbar.unshift({
					id: "edit-stateroom",
					command: "edit-stateroom",
					label: "✏️",
				});
				model.set({ toolbar });
				}
			}
		});

		// ✅ Edit Command
		editor.Commands.add('edit-stateroom', {
			run(editor) {
				const selected = editor.getSelected();
				const row = selected.components().at(0); // .row
				const staterooms = window.yachtStaterooms || [];
			
				const defaultFields = [
					{ key: 'AccTypeName', label: 'Cabin Name', selected: true },
					{ key: 'BedTypeName', label: 'Bed', selected: true },
					{ key: 'TTLGuest', label: 'Guests', selected: true }
				];
			
				const optionalFields = [
					{ key: 'HeadLocationName', label: 'Head Location' },
					{ key: 'NumBed', label: 'Number of Beds' },
					{ key: 'StateroomDimLengthUnit', label: 'Length Unit' }
				];
			
				// 🧠 Read existing selected fields (for pre-checking)
				const prev = row.getAttributes()['data-selected-fields'];
				const previouslySelected = prev ? prev.split(',') : [];
		  
				const modalContent = `
					<form id="stateroom-options-form">
					${[...defaultFields, ...optionalFields].map(f => `
						<div>
						<label>
							<input type="checkbox" name="field" value="${f.key}"
							${f.selected || previouslySelected.includes(f.key) ? 'checked' : ''} />
							${f.label}
						</label>
						<input type="text" name="label-${f.key}" placeholder="Rename label"
							value="${f.label}" class="form-control form-control-sm mt-1 mb-2" />
						</div>
					`).join('')}
					<button type="submit" class="btn btn-primary mt-2">Insert</button>
					</form>
				`;
		  
				editor.Modal.open({
					title: 'Configure Stateroom Display',
					content: modalContent
				});
		  
				document.getElementById('stateroom-options-form').addEventListener('submit', e => {
					e.preventDefault();
					const form = e.target;
					const checkedFields = [...form.querySelectorAll('input[name="field"]:checked')].map(i => i.value);
				
					const customLabels = {};
					[...defaultFields, ...optionalFields].forEach(f => {
					const val = form.querySelector(`input[name="label-${f.key}"]`)?.value || f.label;
					if (checkedFields.includes(f.key)) {
						customLabels[f.key] = val;
					}
					});
				
					// Rebuild the items
					const newItems = staterooms.map((room, index) => ({
					tagName: 'div',
					attributes: {
						class: `col-md-6 stateroom-item p-3${index > 0 ? ' grapesjs-dummy-block' : ''}`
					},
					components: checkedFields.map(key => ({
						tagName: 'div',
						attributes: { class: `stateroom-field yt-${key.toLowerCase()}` },
						components: [
						{ tagName: 'span', attributes: { class: 'staterooms-field-label' }, content: customLabels[key] + ': ' },
						{ tagName: 'span', attributes: { class: 'staterooms-field-value' }, content: room[key] || '' }
						]
					}))
					}));

					if (row) {
						row.components().reset(newItems);
						row.addAttributes({ 'data-selected-fields': checkedFields.join(',') });
					}
					
				
					editor.Modal.close();
				});
			}
		});		 
		//Staterooms end here

		//Toys and Tenders starts here
		const toyTenderData = {
			toys: [
				'Diving Equipment',
				'Flyboard',
				'Glass Bottom Kayaks',
				'Jet Ski',
				'Kayaks',
				'Paddle-Boards',
				'Seabobs',
				'Snorkelling Equipment',
				'Wakeboard',
				'Water-Skis',
			],
			tender: [
				'9m Windy Custom Limo',
				'10.40m Scorpion Rib Jet Tender',
				'7.80m Scorpion Rib Serket Inboard',
				'7.80m Custom Tender',
				'4.50m Bombard Commando C5',
			],
			toysDetails: [
				'Floating Noodles',
            	'Snorkel gear - mask and fins',
			]
		};
		  
		blockManager.add('toys-tender-block', {
			label: 'Toys & Tenders',
			category: 'Charter',
			attributes: { class: 'fa bi bi-bicycle' },
			content: {
			  type: 'toys-tender-block',
			  tagName: 'div',
			  attributes: {
				'data-type': 'toys-tender-block',
				'data-tt-block': 'toys',
				'data-separator': '',
			  },
			  components: [
				{
				  tagName: 'div',
				  attributes: { class: 'tt-list' },
				  components: toyTenderData.toys.flatMap((item, idx, arr) => {
					const elements = [
					  {
						tagName: 'span',
						attributes: { class: 'tt-item-toys' },
						components: [
						  { tagName: 'i', attributes: { class: 'bi bi-arrow-right-short' } },
						  { type: 'textnode', content: item }
						]
					  }
					];
					if (idx < arr.length - 1) {
					  elements.push({ type: 'textnode', content: ', ' });
					}
					return elements;
				  })
				}
			  ]
			}
		});
		  
		editor.DomComponents.addType('toys-tender-block', {
			model: {
			  defaults: {
				traits: [
				  {
					type: 'select',
					name: 'data-tt-block',
					label: 'Type',
					changeProp: 1,
					options: [
					  { value: 'toys', name: 'Toys' },
					  { value: 'tender', name: 'Tender' },
					  { value: 'toysDetails', name: 'toysDetails' },
					],
					default: 'toys',
				  },
				  {
					type: 'text',
					name: 'data-separator',
					label: 'Separator',
					default: '',
					changeProp: 1,
				  }
				]
			  },
			  init() {
				this.on('change:data-tt-block change:data-separator', () => {
				  this.addAttributes({
					'data-tt-block': this.get('data-tt-block'),
					'data-separator': this.get('data-separator'),
				  });
		  
				  const selectedType = this.get('data-tt-block') || 'toys';
				  const separator = this.get('data-separator') || '';
				  const items = toyTenderData[selectedType] || [];
		  
				  const listDiv = this.components().find(comp =>
					comp.getAttributes().class === 'tt-list'
				  );
		  
				  if (listDiv) {
					// Use the first <span> as a template if present
					const existingSpans = listDiv.components().filter(comp =>
					  (comp.getAttributes().class || '').includes('tt-item-')
					);
					let iconTemplate = null;
		  
					if (existingSpans.length > 0) {
					  iconTemplate = existingSpans.at(0).components().find(comp => {
						const tag = comp.get('tagName');
						return tag === 'i' || tag === 'svg' || tag === 'img';
					  });
					}
		  
					// Reset and regenerate content
					listDiv.components().reset([]);
		  
					items.forEach((item, idx) => {
					  const innerComponents = [];
		  
					  // Clone icon if available
					  if (iconTemplate) {
						innerComponents.push(iconTemplate.clone());
					  }
		  
					  // Add text node
					  innerComponents.push({ type: 'textnode', content: item });
		  
					  listDiv.append({
						tagName: 'span',
						attributes: { class: `tt-item-${selectedType}` },
						components: innerComponents
					  });
		  
					  if (idx < items.length - 1 && separator) {
						listDiv.append({ type: 'textnode', content: separator });
					  }
					});
				  }
				});
			  }
			},
			view: {
			  init() {
				this.listenTo(this.model, 'change:attributes', this.render);
			  }
			}
		});		  
		  
		//Toys and Tenders end here

		//Charter Rates Block starts here
		blockManager.add('rates-block', {
			label: 'Rates',
			category: 'Charter',
			attributes: { class: 'fa bi bi-coin' },
			content: {
			  type: 'rates-block',
			  tagName: 'div',
			  attributes: {
				'data-type': 'charter-rates',
				class: 'yt-charter-rates-wrapper',
			  },
			  components: [
				{
				  tagName: 'div',
				  attributes: { class: 'yt-charter-rates-row row' },
				  style: {
					'margin-bottom':'20px'
				  },
				  components: [
					{
					  tagName: 'div',
					  attributes: { class: 'col-12 col-md-6' },
					  components: [
							{
							tagName: 'div',
							attributes: { class: 'charter-spec yt-charter-season' },
							components: [
								{
									tagName: 'span',
									attributes: {
										class: 'charter-spec-label',
									},
									style: {
										'font-weight':'700',
										'padding-right':'5px'
									},
									editable: true,
									components: [
									  { type: 'text', content: 'SEASON' }
									]
								},
								{
								tagName: 'span',
								attributes: { class: 'charter-spec-val' },
								content: 'Summer 2024'
								}
							]
							},
							{
							tagName: 'div',
							attributes: { class: 'charter-spec yt-charter-low-rate' },
							components: [
								{
									tagName: 'span',
									attributes: {
										class: 'charter-spec-label',
									},
									style: {
										'font-weight':'700',
										'padding-right':'5px'
									},
									editable: true,
									components: [
									  { type: 'text', content: 'PRICE (low)' }
									]
								},
								{
								tagName: 'span',
								attributes: { class: 'charter-spec-val' },
								content: '€2,000,000 EUR/Per Week'
								}
							]
							},
							{
							tagName: 'div',
							attributes: { class: 'charter-spec yt-charter-high-rate' },
							components: [
								{
									tagName: 'span',
									attributes: {
										class: 'charter-spec-label',
										
									},
									style: {
										'font-weight':'700',
										'padding-right':'5px'
									},
									editable: true,
									components: [
									  { type: 'text', content: 'PRICE (high)' }
									]
								},
								{
								tagName: 'span',
								attributes: { class: 'charter-spec-val' },
								content: '€2,200,000 EUR/Per Week'
								}
							]
							}
					  	]
					},
					{
					  tagName: 'div',
					  attributes: { class: 'col-12 col-md-6' },
					  components: [
						{
						  tagName: 'div',
						  attributes: {
							class: 'charter-spec yt-charter-location',
							style: 'margin-bottom: 20px'
						  },
						  components: [
							{
								tagName: 'div',
								attributes: {
									class: 'charter-spec-label',
								},
								style: {
									'font-weight':'700',
								},
								components: [
								  { type: 'text', content: 'LOCATION', editable: true }
								]
							},
							{
							  tagName: 'span',
							  attributes: { class: 'charter-spec-val' },
							  content: 'Corsica, Croatia, Cyprus, French Riviera, Greece, Italy, Malta, Sardinia, Spain, Turkey'
							}
						  ]
						}
					  ]
					}
				  ]
				}
			  ]
			}
		});		  
		  
		//Charter Rates Block ends here
		  
		// ✅ Define api Variables Array
		function convertLiteralsToDropdown(dataObject) {
			return Object.entries(dataObject).flatMap(([key, value]) => {
				if (Array.isArray(value)) {
					return value.map(item => `${key}:${item}`);
				}
				return `${value}`;
			});
		}
		
		// ✅ Usage
		const listing_literals_yacht   = convertLiteralsToDropdown(yatcoData.listing_literals_yacht);
		const single_literals_yacht    = convertLiteralsToDropdown(yatcoData.single_literals_yacht);
		const listing_literals_charter = convertLiteralsToDropdown(yatcoData.listing_literals_charter);
		const single_literals_charter  = convertLiteralsToDropdown(yatcoData.single_literals_charter);

		// ✅ Allow Changing Variable Dynamically with this Function
		// Make selectedContext global
		let selectedContext = null;

		editor.Commands.add("change-variable", {
			run(editor, sender) {
				const selected = editor.getSelected();
				if (!selected) return;

				// If selectedContext is already set, skip asking and directly show variables
				if (selectedContext) {
					showVariableSelector(editor, selected);
					return;
				}

				// Step 1: Ask for variable context if not yet selected
				const modalContent = `
					<label><strong>Select Variable Source:</strong></label>
					<select id="variable-context" class="form-select mb-3">
						<option value="listing_literals_yacht">Listings Page - Sale</option>
						<option value="listing_literals_charter">Listings Page - Charter</option>
						<option value="single_literals_yacht">Single Page - Sale</option>
						<option value="single_literals_charter">Single Page - Charter</option>
					</select>
					<button id="continue-variable-select" class="btn btn-primary">Next</button>
				`;

				editor.Modal.open({
					title: "Choose Variable Source",
					content: modalContent,
				});

				// After context is selected
				document.getElementById("continue-variable-select").addEventListener("click", function () {
					selectedContext = document.getElementById("variable-context").value;
					showVariableSelector(editor, selected);
				});
			},
		});

		// Extracted this as a separate helper function
		function showVariableSelector(editor, selected) {
			let variableOptions = [];
			switch (selectedContext) {
				case "listing_literals_yacht":
					variableOptions = listing_literals_yacht;
					break;
				case "listing_literals_charter":
					variableOptions = listing_literals_charter;
					break;
				case "single_literals_yacht":
					variableOptions = single_literals_yacht;
					break;
				case "single_literals_charter":
					variableOptions = single_literals_charter;
					break;
			}

			const currentVariable = selected.getAttributes()["data-variable"] || "";
			const cleanVariable = currentVariable.replace(/[{}]/g, "");

			const variableDropdown = variableOptions
				.map((v) => `<option value="${v}" ${v === cleanVariable ? "selected" : ""}>${v}</option>`)
				.join("");

			const modalContent_ = `
				<label><strong>Select a Variable:</strong></label>
				<select id="variable-selector" class="form-select mb-3">${variableDropdown}</select>
				<button id="insert-variable" class="btn btn-success">Insert</button>
			`;

			editor.Modal.open({
				title: "Choose Variable Source",
				content: modalContent_,
			});
			
			document.getElementById("insert-variable").addEventListener("click", function () {
				const selectedVar = document.getElementById("variable-selector").value;
				const formattedVar = `{${selectedVar}}`;
				selected.set("content", formattedVar);
				selected.addAttributes({
					"data-variable": selectedVar,
					"data-context": selectedContext,
				});
				editor.Modal.close();
			});
		}
		

		// ✅ Allow Changing Icons Dynamically with Async Function
		editor.Commands.add("change-icon", {
			run(editor, sender) {
				const selected = editor.getSelected();
				if (selected && selected.is("icon")) {
					editor.Modal.open({
						title: "Select an Icon",
						content: `
							<div style="text-align: center; padding: 10px;">
								<label for="icon-picker">Choose an Icon:</label>
								<input type="text" id="icon-picker" class="icon-picker" placeholder="Click to select an icon">
								<div id="icon-preview" style="margin-top: 10px; font-size: 24px;"></div>
								<button id="save-icon" style="margin-top: 10px;">Save</button>
							</div>
						`,
					});
					
					// ✅ Initialize FontAwesome Icon Picker (Assuming FontAwesome is Loaded)
					jQuery(".icon-picker").iconpicker({
						title: "Pick an Icon",
						placement: "bottomLeft",
						animation: false,
						hideOnSelect: true,
						selected: true,
					});
					
					// ✅ Handle Icon Selection
					jQuery(".icon-picker").on("iconpickerSelected", function (event) {
						let selectedIcon = event.iconpickerValue;
						
						// Get the selected component
						const selected = editor.getSelected();
						
						if (selected) {
							// ✅ Replace the class only (prevents double icons)
							selected.addAttributes({ class: `fa ${selectedIcon}` });

							// ✅ Ensure it does not wrap another <i> inside <i>
							selected.set("content", ""); // Clear nested content
						}

						editor.Modal.close(); // Close modal after selecting
					});
					
				}
			},
		});

		editor.Commands.add("change-bi-icon", {
			run(editor, sender, options = {}) {
			const selected = editor.getSelected();
			if (!selected || selected.get("type") !== "b-icon") return;
		
			const el = selected.view?.el;
			if (!el) return;
		
			// Open the icon picker modal
			window.selectBootstrapIcon(el);
			},
		});

		editor.Commands.add("set-device-desktop", {
			run: (editor) => editor.setDevice("Desktop"),
		});

		editor.Commands.add("set-device-tablet", {
			run: (editor) => editor.setDevice("Tablet"),
		});

		editor.Commands.add("set-device-mobile", {
			run: (editor) => editor.setDevice("Mobile"),
		});

		editor.Commands.add("toggle-fullscreen", {
			run: function () {
				let editorContainer = document.documentElement;
				if (!document.fullscreenElement) {
					editorContainer.requestFullscreen();
				} else {
					if (document.exitFullscreen) {
						document.exitFullscreen();
					}
				}
			},
		});

        // ✅ Preview Command
        editor.Commands.add("toggle-preview", {
			run(editor) {
				editor.stopCommand("sw-visibility"); // Hide all controls
				document.getElementById("blocks").style.display = "none";
				document.getElementById("panel__right").style.display = "none";
			},
			stop(editor) {
				editor.runCommand("sw-visibility"); // Show controls back
				document.getElementById("blocks").style.display = "block";
				document.getElementById("panel__right").style.display = "block";
			},
		});

		editor.Commands.add("toggle-component-layout", {
			run(editor) {
				const canvasDoc = editor.Canvas.getDocument();
				canvasDoc.querySelectorAll('.gjs-component').forEach(el => {
				el.classList.toggle("gjs-default-layout");
				});
			}
		});

		// ✅ Full Width Command
		editor.Commands.add("full-width", {
			run() {
				const editorContainer = document.querySelector(".editor__container");
				const rootElement = document.documentElement;
				
				if (!document.fullscreenElement) {
				// Request full screen and add the class on success
				rootElement.requestFullscreen()
					.then(() => {
					if (editorContainer) {
						editorContainer.classList.add("full-width");
					}
					})
					.catch(err => console.error("Error while entering full-screen:", err));
				} else {
				// Exit full screen and remove the class on success
				document.exitFullscreen()
					.then(() => {
					if (editorContainer) {
						editorContainer.classList.remove("full-width");
					}
					})
					.catch(err => console.error("Error while exiting full-screen:", err));
				}
			}
		});

		// ✅ Adjust Toolbar Position to Stay Inside #gjs
		editor.on("component:selected", (model) => {

			if (model.get("type") === "variable") {
				const toolbar = model.get("toolbar") || [];
				const hasChangeIconButton = toolbar.some((btn) => btn.command === "change-variable");
				if (!hasChangeIconButton) {
					toolbar.unshift({
						id: "change-variable",
						command: "change-variable",
						label: "✏️",
					});
					model.set({ toolbar });
				}
			}

			if (model.is("icon")) {
				const toolbar = model.get("toolbar") || [];
				const hasChangeIconButton = toolbar.some((btn) => btn.command === "change-icon");
				if (!hasChangeIconButton) {
					toolbar.unshift({
						id: "change-icon",
						command: "change-icon",
						label: "✏️",
					});
					model.set({ toolbar });
				}
			}

			// Check if it's a b-icon type
			if (model.get("type") === "b-icon") {
				const toolbar = model.get("toolbar") || [];
				const hasChangeIconButton = toolbar.some((btn) => btn.command === "change-bi-icon");
				if (!hasChangeIconButton) {
					toolbar.unshift({
						id: "change-bi-icon",
						command: "change-bi-icon",
						label: "✏️",
					});
					model.set({ toolbar });
				}
			}
			
			setTimeout(() => {
				const frame = document.querySelector(".gjs-frame");
				if (!frame) return;
				
				const frameDocument = frame.contentDocument || frame.contentWindow.document;
				const toolbarEl = document.querySelector(".gjs-toolbar");
				const editorContainer = frameDocument.body;

				if (toolbarEl && editorContainer) {
					const rect = toolbarEl.getBoundingClientRect();
					const containerRect = editorContainer.getBoundingClientRect();

					let toolbarLeft = rect.left - containerRect.left;
					let toolbarTop = rect.top - containerRect.top;
					const toolbarWidth = rect.width;
					const toolbarHeight = rect.height;
					const containerWidth = containerRect.width;
					const containerHeight = containerRect.height;

					if( containerWidth - (toolbarLeft + toolbarWidth) > toolbarWidth ){
						toolbarEl.style.left = `10px`;//temporary fix
					}              
										
				}
			}, 200);
		});

		//For the right panel buttons
		editor.Panels.addPanel({
			id: 'views-panel',
			el: '.panel__right-buttons', // This is where the buttons will appear
			buttons: [
				{
				id: 'btn-styles',
				className: 'btn btn-default',
				label: 'Styles',
				command: 'open-styles',
				active: true, // Styles are visible by default
				togglable: true,
				},
				{
				id: 'btn-traits',
				className: 'btn btn-default',
				label: 'Traits',
				command: 'open-traits',
				togglable: false,
				},
				{
				id: 'btn-layers',
				className: 'btn btn-default',
				label: 'Layers',
				command: 'open-layers',
				togglable: false,
				},
			]
		});

		editor.Commands.add('toggle-selectors', {
			run(editor, sender) {
			  const container = document.querySelector('.gjs-selectors-container');
			  if (!container) return;
		  
			  container.style.display = container.style.display === 'none' ? 'block' : 'none';
			  sender?.set('active', container.style.display === 'block');
			},
			stop(editor, sender) {
			  const container = document.querySelector('.gjs-selectors-container');
			  if (!container) return;
		  
			  container.style.display = 'none';
			  sender?.set('active', false);
			}
		});
		  
		//Open the style tab and close the traits and layer tabs
        editor.Commands.add('open-styles', {
			run(editor) {
				document.querySelector('.styles-container').style.display = 'block';
				document.querySelector('.traits-container').style.display = 'none';
				document.querySelector('.layers-container').style.display = 'none';
			}
        });
        
		//Open the traits tab and close the style and layer tabs
        editor.Commands.add('open-traits', {
			run(editor) {
				document.querySelector('.traits-container').style.display = 'block';
				document.querySelector('.styles-container').style.display = 'none';
				document.querySelector('.layers-container').style.display = 'none';
			}
        });

		//Open the layer tab and close the style and traits tabs
        editor.Commands.add('open-layers', {
			run(editor) {
				document.querySelector('.layers-container').style.display = 'block';
				document.querySelector('.styles-container').style.display = 'none';
				document.querySelector('.traits-container').style.display = 'none';
			}
        });
        
		// When editor is ready, load the saved template from localized data
		editor.on('load', function() {
			const sectors = editor.StyleManager.getSectors();
			sectors.each((sector) => {
				sector.on('change:open', (model, value) => {
				if (value) {
					// Close all other sectors
					sectors.each((otherSector) => {
					if (otherSector !== model) {
						otherSector.set('open', false);
					}
					});
				}
				});
			});
			
			const styleManager = editor.StyleManager;
			const fontProperty = styleManager.getProperty('typography', 'font-family');
			if(fontProperty) {
				fontProperty.setOptions(allFonts); // allFonts is your merged array
				styleManager.render(); // Re-render the style manager to reflect changes
			} else {
				console.warn('Font property not found.');
			}

			const canvasDoc = editor.Canvas.getDocument();
			const styleEl = canvasDoc.createElement('style');
			styleEl.type = 'text/css';

			let css = `.gjs-default-layout {
							outline: 1px solid #cccccc;
						}
						.svg-icon svg{
							width:100%;
							height:100%;
						}
						.svg-icon path{
							fill : #747474;
						}
						.image-has-hover-effects, .gjs-image-wrapper{
							overflow: hidden;
						}
						.image-has-hover-effects .gjs-image {
							transition: transform 0.4s ease;
						}
						.image-has-hover-effects:hover .gjs-image {
						transform: scale(1.1);
						}
						`;

			yatcoData.custom_fonts_js.forEach(font => {
				if(font.file_url) {
				css += `
					@font-face {
					font-family: '${font.font_family}';
					src: url('${font.file_url}') format('woff2');
					font-weight: ${font.font_weight};
					font-style: ${font.font_style};
					}
				`;
				}
			});

			styleEl.innerHTML = css;
			canvasDoc.head.appendChild(styleEl);
			
			if (yatcoData.template_name && yatcoData.template_name.trim() !== '') {
				
				// Update the text field with the template name
				var templateNameField = document.getElementById('yt_template_name');
				if (templateNameField) {
					templateNameField.value = yatcoData.template_name;
				}
				// Update the text field with the template name
				var no_of_grids = document.getElementById('yt_cards_count');
				
				if (no_of_grids) {
					no_of_grids.value = yatcoData.no_of_grids;
				}

				if (yatcoData.editor_contents) {
					editor.setComponents(JSON.parse(yatcoData.editor_contents));
				}
				if (yatcoData.styles) {
					editor.setStyle(yatcoData.styles);
				}
			}
		});          
        
		editor.Commands.add('update-template-preview', {
			run(editor, sender) {
				sender && sender.set('active', 0); // deactivate the button if needed
			
				function proceedWithPreview() {
					let content = editor.getHtml();
					let style = editor.getCss();
			
					content = window.processTemplateContent(content);
					const updatedStyle = processTemplateStyle(style);
			
					jQuery.ajax({
					url: yatcoData.ajax_url,
					type: 'POST',
					data: {
						action: 'yatco_update_template_preview',
						content: content,
						updatedStyle: updatedStyle,
						templateType: window.templateType,
						templateFor: window.templateFor,
					},
					beforeSend: function () {
						editor.Modal.setTitle('Loading Preview');
						editor.Modal.setContent('<div class="loader-spinner">Please wait...</div>');
						editor.Modal.open();
					},
					success: function (response) {
						editor.Modal.close();
						window.open(response, '_blank');
					},
					error: function (error) {
						console.error('Error updating template preview:', error);
					}
					});
				}
			
				// If the required globals aren't set, show a combined modal
				if (!window.templateType || !window.templateFor) {
					const modal = editor.Modal;
					modal.setTitle('Select Template Options');
			
					const modalContent = document.createElement('div');
					modalContent.innerHTML = `
					<label for="templateTypeSelect"><strong>Template Type:</strong></label>
					<select id="templateTypeSelect" style="margin-bottom: 10px;" class="form-control">
						<option value="grid">Grid</option>
						<option value="list">List</option>
						<option value="single">Single</option>
					</select>
			
					<label for="templateForSelect"><strong>Template For:</strong></label>
					<select id="templateForSelect" style="margin-bottom: 10px;" class="form-control">
						<option value="sale">Sale</option>
						<option value="charter">Charter</option>
					</select>
			
					<button id="templateTypeOk" class="btn btn-primary mt-2">Continue</button>
					`;
			
					modal.setContent(modalContent);
			
					modalContent.querySelector('#templateTypeOk').addEventListener('click', function () {
					window.templateType = modalContent.querySelector('#templateTypeSelect').value;
					window.templateFor = modalContent.querySelector('#templateForSelect').value;
					modal.close();
					proceedWithPreview();
					});
			
					modal.open();
				} else {
					proceedWithPreview();
				}
			}
		});		  
        
		// ✅ Save content to WordPress Database
		editor.Commands.add("save-content", {
			
			run: function (editor) {
				let editor_content = editor.getComponents();
				let content = editor.getHtml();
				let style = editor.getCss();

				editor_content = JSON.stringify(editor_content);
				
				let templateInput = document.getElementById("yt_template_name"); // Get Template Name
				let templateName = templateInput.value.trim(); // Trim spaces
				let yt_cards_count = document.getElementById("yt_cards_count").value.trim(); // Get card count
				
				const is_default = document.getElementById("is_default");
				let is_default_checked = false;

				if (is_default && is_default.checked) {
					is_default_checked = is_default.checked;
				}
				
				// ✅ Check if templateName is empty
				if (!templateName) {
					templateInput.focus();
					alert("Template name cannot be empty!");
					return; // ❌ Exit function if empty
				}

				let editFlag = false;

				if (yatcoData.template_name && yatcoData.template_name.trim() !== '') {
					editFlag = true;
				}
				const templateID = yatcoData.template_id;

				// Process the content using our custom function
				content = window.processTemplateContent(content);
				const updatedStyle = processTemplateStyle(style);

				jQuery.post(yatcoData.ajax_url, {
					action: "save_grapesjs_content",
					yt_template_name: templateName,
					yt_cards_count: yt_cards_count,
					yt_html_content: content,//manipuated contents for rendering in frontend
					yt_html_editor_content: editor_content,//saving the editor content as it is.
					editTemplate : editFlag,
					templateID : templateID,
					styleString: style,
					updatedstyleString: updatedStyle,
					is_default: is_default_checked,
					security: yatcoData.plugin_nonce
				}, function (response) {
					alert(response);
			});

			},
		});
        
    } else {
        console.error("GrapesJS failed to load.");
    }
  
});