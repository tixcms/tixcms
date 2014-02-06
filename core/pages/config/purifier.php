<?php

$config['purifier'] = array(
    'HTML.AllowedAttributes'=>array('a.href', 'img.src', 'img.style', 'img.alt'),
    'HTML.AllowedElements'=>array(
        'p', 'strong', 'a', 'b', 'em', 'del', 'blockquote', 'code', 'pre', 'img',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
    ),
    'HTML.Nofollow'=>true,
    'AutoFormat.AutoParagraph'=>true,
    'AutoFormat.Linkify'=>true,
    'AutoFormat.RemoveEmpty'=>true
);