<?php
/**
 * @component     Plugin CG Résa
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2025 ConseilGouz. All Rights Reserved.
 * @author ConseilGouz
**/

namespace ConseilGouz\Plugin\Content\Cgresa\Extension;

// No direct access.
defined('_JEXEC') or die();
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

final class Cgresa extends CMSPlugin implements SubscriberInterface
{
    protected $autoloadLanguage = true;
    public $myname = 'Cgresa';

    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare'   => 'onPrepare',
        ];
    }

    public function onPrepare($event) // $context, $row, $params, $page = 0)
    {
        $row = $event[1];
        $plg	= 'media/plg_content_cgresa/';
        /** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->registerAndUseStyle('cgresa', $plg.'css/style.css');
        $this->LoadLanguage();

        $regex = '/{(resa=)\s*(.*?)}/i';
        preg_match_all($regex, $row->text, $matches);
        $count = count($matches[0]);
        for ($i = 0; $i < $count; $i++) {
            $r  = str_replace('{resa=', '', $matches[0][$i]);
            $r  = str_replace('}', '', $r);
            $time = "";
            $msg = "";
            $ex = explode('|', $r);
            $date	= $ex[0];
            $date = str_replace('/', '-', $date);
            if (array_key_exists('1', $ex)) {
                if (strpos($ex[1], 'time') !== false) {
                    $time = str_replace('time=', '', $ex[1]);
                }
                if (strpos($ex[1], 'msg') !== false) {
                    $msg = str_replace('msg=', '', $ex[1]);
                }
            }
            if (array_key_exists('2', $ex)) {
                if (strpos($ex[2], 'msg') !== false) {
                    $msg = str_replace('msg=', '', $ex[2]);
                }
            }
            $replace = '<a class="plg_cg_resa" title="'.Text::_("PLG_CG_RESA_RESA").'" href="index.php?option=com_cgresa&amp;view=resa&layout=resa&date='.$date.'&time='.$time.'&msg='.$msg.'">';
            $replace .= $ex[0].'</a>';
            $row->text = str_replace($matches[0][$i], $replace, $row->text);
        }

        return true;
    }
}
