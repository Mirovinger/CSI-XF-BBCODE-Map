<?php

class CSI_BbCodeMap_BbCode_Formatter_Base
{
  public static function getBbCodeMap(array $tag, array $rendererStates, XenForo_BbCode_Formatter_Base $formatter)
  {
    $xenOptions = XenForo_Application::get('options');
    $visitor    = XenForo_Visitor::getInstance();
    $visitorId  = $visitor->getUserId();

    $option     = explode('|', $tag['option']);
    $option     = array_map('trim', $option);

    if (count($option) > 1) {
      $optDefault = $option[0];
    } else {
      $optDefault = $tag['option'];
    }

    $content  = $formatter->renderSubTree($tag['children'], $rendererStates);

    if (!preg_match('#^(.*?)$#', $content)) {
      return $formatter->renderInvalidTag($tag, $rendererStates);
    }

    $mapId    = $visitorId . '-' . $content . '-' . uniqid();
    $mapId    = hash('sha1', $mapId);

    $view     = $formatter->getView();

    if ($view) {
      $template = $view->createTemplateObject('csiXF_bbCodeMap_tag_map',
        array(
          'content' => $content,
          'mapId'   => $mapId,
        ));

      $content = $template->render();
      return trim($content);
    }

    return $content;
  }
}
