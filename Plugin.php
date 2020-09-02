<?php namespace TingFeng\NewsZhCnLang;

use Backend;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use October\Rain\Translation\Translator;
use System\Classes\PluginBase;

/**
 * NewsZhCnLang Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Indikator.News'];
    /**
     * @var Translator
     */
    protected $translator;
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'NewsZhCnLang',
            'description' => 'Indikator.News 插件的中文语言包',
            'author'      => 'TingFeng',
            'icon'        => 'icon-leaf'
        ];
    }

    protected $loaded = [];
    public function boot()
    {
        $this->translator = app()->get("translator");
        Event::listen("translator.beforeResolve", function ($key, $replace, $locale) {
            list($namespace, $group, $item) = $this->translator->parseKey($key);
            if ($namespace === "indikator.news" && $this->translator->getLocale() === "zh-cn") {
                $locale = $this->translator->getLocale();
                $namespace = "tingfeng.newszhcnlang";
                if (!isset($this->loaded[$namespace][$group][$locale])) {
                    $lines = $this->translator->getLoader()->load(
                        $locale,
                        $group,
                        $namespace
                    );

                    $this->loaded[$namespace][$group][$locale] = $lines;

                    $line = Arr::get($this->loaded[$namespace][$group][$locale], $item);
                } else {
                    $line = Arr::get($this->loaded[$namespace][$group][$locale], $item);
                }
                return $line;
            }
            return false;
        });
    }
}
