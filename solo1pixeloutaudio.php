<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentSOLO1pixeloutAudio extends JPlugin
{
	var $isHeaderSet = FALSE;
	
	function plgContentSOLO1pixeloutAudio( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}
	
	function addHeaders()
	{
		if($this->isHeaderSet) return;
		$this->isHeaderSet = TRUE;
		
		$document = &JFactory::getDocument();
		$document->addCustomTag('<script src="'.JURI::base().'plugins/content/solo1pixeloutaudio/audio-player.js" type="text/javascript"></script>');
		$document->addCustomTag('<script type="text/javascript">AudioPlayer.setup("'.JURI::base().'plugins/content/solo1pixeloutaudio/player.swf", {width: 300});</script>');
	}

	function onPrepareContent( &$article, &$params, $limitstart )
	{	
		$res = preg_match("/\[audio (.*)](.*)\[\/audio\]/", $article->text, $matches);
		if($res && count($matches) > 2)
		{
			$this->addHeaders();
			
			$options = $matches[1];
			$label = $matches[2];
			
			$ex1 = explode(' ', $options);
			$options = array();
			foreach($ex1 as $itm)
			{
				list($key, $val) = explode('=', $itm);
				$options[$key] = $val;
			}
			
			if(!empty($options['src']))
			{
				$label = str_replace('"', '\"', $label);
				$width = '';
				if(!empty($options['width'])) $width = ', width: "'.$options['width'].'"';
				$tmp =<<<END
<p id="audioplayer_1">&nbsp;</p>  
<script type="text/javascript">  
AudioPlayer.embed("audioplayer_1", {soundFile: "{$options[src]}", animation: "no", titles: "{$label}"{$width}});  
</script>
END;
				$article->text = str_replace($matches[0], $tmp, $article->text);
			}
		}
	}
}
