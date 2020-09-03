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
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'NewsZhCnLang',
            'description' => 'Chinese language pack for Indikator.News plug-ins',
            'author'      => 'TingFeng',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * 国际化类
     * @var Translator
     */
    protected $translator;

    /**
     * @var array 已加载的国际化数据
     */
    protected $loaded = [];

    public function boot()
    {
        $this->translator = app()->get("translator");
        Event::listen("translator.beforeResolve", function ($key, $replace, $locale) {
            list($namespace, $group, $item) = $this->translator->parseKey($key);
            if ($namespace === "indikator.news" && $this->translator->getLocale() === "zh-cn") {
                $locale = $this->translator->getLocale();
                $namespace = "tingfeng.newszhcnlang";
                if (!$this->isLoaded($namespace, $group, $locale)) {
                    $lines = $this->getLines($namespace, $group, $locale);

                    $this->loaded[$namespace][$group][$locale] = $lines;
                }
                return $this->getLine($namespace, $group, $locale, $item);
            }
            return null;
        });
    }

    /**
     * 检查是否已加载
     * @param string $namespace
     * @param string $group
     * @param string|null $locale
     * @return bool
     */
    protected function isLoaded(string $namespace, string $group, string $locale = null)
    {
        return isset($this->loaded[$namespace][$group][$locale]);
    }

    /**
     * 获取所有行
     * @param string $namespace
     * @param string $group
     * @param string $locale
     * @return array
     */
    protected function getLines(string $namespace, string $group, string $locale)
    {
        return $this->translator->getLoader()->load($locale, $group, $namespace);
    }

    /**
     * 获取一行
     * @param string $namespace
     * @param string $group
     * @param string $locale
     * @param string $item
     * @return mixed
     */
    protected function getLine(string $namespace, string $group, string $locale, string $item)
    {
        return Arr::get($this->loaded[$namespace][$group][$locale], $item);
    }
}
