<?php echo '<?xml version="1.0"?>'?>
<rss version="2.0">
  <channel>
    <title><?=$title?></title>
    <link><?=$link?></link>
    <description><?=$description?></description>
    <language><?=$lang?></language>

    <?php foreach( $items as $item ):?>
        <item>
          <title><?=$item->title?></title>
          <link><?=$item->link?></link>
          <description><![CDATA[<?=$item->description?>]]></description>
          <pubDate><?=date('r', $item->date)?></pubDate>
          <?php if( isset($item->category) ):?>
            <category><?=$item->category?></category>
          <?php endif?>
          
          <?php if( isset($item->author) ):?>
            <author><?=$item->author?></author>
          <?php endif?>
        </item>
    <?php endforeach;?>
  </channel>
</rss>