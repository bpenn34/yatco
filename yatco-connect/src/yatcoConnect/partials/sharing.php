<ul class="share-buttons" data-source="simplesharingbuttons.com">
  <li><a data-tracking-label="Facebook" href="https://www.facebook.com/sharer/sharer.php?u=&quote=" title="Share on Facebook" target="_blank" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&quote=' + encodeURIComponent(document.URL)); return false;"><img alt="Share on Facebook" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>Facebook.png" /></a></li>
  
  <li><a data-tracking-label="Twitter" href="https://twitter.com/intent/tweet?source=&text=:%20&via=searchyatco" target="_blank" title="Tweet" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20'  + encodeURIComponent(document.URL)); return false;"><img alt="Tweet" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>x.png" /></a></li>

  <li><a data-tracking-label="LinkedIn" href="http://www.linkedin.com/shareArticle?mini=true&url=&title=&summary=&source=" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><img alt="Share on LinkedIn" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>LinkedIn.png" /></a></li>
  
  <li>
    <a data-tracking-label="WhatsApp" 
        href="https://api.whatsapp.com/send/?text=" 
        title="Share on WhatsApp" 
        target="_blank" 
        style="text-decoration:none;"
        rel="noopener noreferrer"
        onclick="window.open('https://api.whatsapp.com/send/?text=' + encodeURIComponent('Check out this: ' + document.title + ' ' + document.URL)); return false;">
        <img alt="share" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>whatsapp.png" />
    </a>
  </li>
  
  <li><a data-tracking-label="Mail" href="mailto:?subject=&body=:%20" target="_blank" title="Send email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><img alt="Send email" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>Email.png" /></a></li>
</ul>

<style type="text/css">
  ul.share-buttons{
    list-style: none;
    padding: 0;
  }

  ul.share-buttons li{
    display: inline;
  }

  ul.share-buttons .sr-only{
    position: absolute;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px, 1px, 1px, 1px);
    padding: 0;
    border: 0;
    height: 1px;
    width: 1px;
    overflow: hidden;
  }
</style>