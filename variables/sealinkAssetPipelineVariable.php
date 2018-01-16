<?php namespace Craft;

class SealinkAssetPipelineVariable
{

  protected $minimee = null;

  public function __construct()
  {
    //need to instantiate minimee's Variable interface since it has some logic
    //not in the Service interface regarding tagging and caching assets
    //otherwise we would get the minimee plugin this way craft()->plugins->getPlugin('minimee')
    if(craft()->plugins->getPlugin('minimee')->settings->enabled) {
      Craft::import('plugins.minimee.variables.MinimeeVariable');
      $this->minimee = new MinimeeVariable();
    }
  }

  public function css()
  {
    $assets = [];
    foreach (craft()->plugins->call('definesResources') as $handle => $anyResources)
    {
      if ($anyResources) {
        foreach (craft()->plugins->getPlugin($handle)->getCssResourcePaths() as $path)
        {
          $assets[] = strtolower($handle) . '/resources/' . $path;
        }
      }
    }
    return $this->minimee->css($assets, $this->overrideSettings());
  }

  public function js()
  {
    $assets = [];
    foreach (craft()->plugins->call('definesResources') as $handle => $anyResources)
    {
      if ($anyResources) {
        foreach (craft()->plugins->getPlugin($handle)->getJsResourcePaths() as $path)
        {
          $assets[] = strtolower($handle) . '/resources/' . $path;
        }
      }
    }
    return $this->minimee->js($assets, $this->overrideSettings());
  }

  private function overrideSettings()
  {
    return ['filesystemPath' => '/app/craft/plugins'];
  }

}
