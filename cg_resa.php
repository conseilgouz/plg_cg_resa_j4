<?php
/**
 * @component     Plugin CG Résa
 * Version			: 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2021 ConseilGouz. All Rights Reserved.
 * @author ConseilGouz 
**/

// No direct access.
defined('_JEXEC') or die();
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class PlgContentCg_Resa extends JPlugin
{
	public function __construct(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	public function onContentPrepare($context, $row, $params, $page = 0)
	{
		$document = Factory::getDocument();
		$document->addStyleSheet(JURI::base() . "plugins/content/cg_resa/css/style.css");
		$this->LoadLanguage();

		$regex = '/{(resa=)\s*(.*?)}/i';
		preg_match_all($regex, $row->text, $matches);
		$count = count($matches[0]);
		for ($i = 0; $i < $count; $i++)
		{
                    $r  = str_replace('{resa=', '', $matches[0][$i]);
                    $r  = str_replace('}', '', $r);
                    $time= "";
                    $msg="";
                    $ex = explode('|', $r);
                    $date	= $ex[0];
                    $date = str_replace('/','-',$date);
                    if (array_key_exists('1', $ex)) {
                        if (strpos($ex[1],'time') !== false)  $time = str_replace('time=','',$ex[1]);
                        if (strpos($ex[1],'msg') !== false)  $msg = str_replace('msg=','',$ex[1]);
                    }
                    if (array_key_exists('2', $ex)) {
                        if (strpos($ex[2],'msg') !== false)  $msg = str_replace('msg=','',$ex[2]);
                    }
					$time = str_replace('h',':',$time);
                    $replace = '<a class="plg_cg_resa" title="'.Text::_("PLG_CG_RESA_RESA").'" href="index.php?option=com_cgresa&amp;view=resa&layout=resa&date='.$date.'&time='.$time.'&msg='.$msg.'">';
                    $replace .= $ex[0].'</a>';
                    $row->text = str_replace($matches[0][$i], $replace, $row->text);
		}

		return true;
	}
}
