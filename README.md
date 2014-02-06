# wp-modx-integration

Wordpress Plugin which provides template functions to access the MODx API and seamlessly integrate your Wordpress &amp; MODx sites.

## Description

This plugin provides two template functions that allow you to use the site's MODx core to execute MODx chunks and snippets and return the output, so that it can be displayed in WP templates.

This has been tested with sites that have the MODx site installed in the root and wordpress installed in a sub-folder - often `/blog` - like this:

```
    /
    ├── /assets
    ├── /blog
    │    ├── wp-admin
    │    ├── wp-content
    │    └── wp-includes
    ├── /manager
    │
    ├── index-ajax.php
    ├── index.php
    └── robots.txt
```

If you have a different structure, you might need to change the `$manager_path = '../manager';` at the top of `modx_init.php`. This setting is used to find your MODx install's `/manager/includes/config.inc.php` file, so that the MODx settings can be read in.

These global MODx settings can be overridden just for this plugin by editing the `$modx_config` array in `modx_config.php`. Currently the only setting from here which is used is `base_url`. If you want to change this, you can edit this section at the bottom of `modx_init.php`:


```php
    // Get plugin local config
    include_once('modx_config.php');
    // Selectively override global modx config from plugin local version
    $modx->config['base_url'] = $modx_config['base_url'];
    $modx->config['site_url'] = $modx_config['base_url'];
```

### modxGetChunk($chunk_name)

Execute the MODx chunk `$chunk_name` and return the rendered result as a string.

E.g: To run the MODx chunk called `footer` and output the result in your Wordpress footer, add the following to your theme's `footer.php`:

```php
    <?php modxGetChunk('footer'); ?>
```

### modxRunSnippet($snippet_name, $param_array)

Execute the MODx snippet `$snippet_name` and return the rendered result as a string.

E.g: To replicate these two MODx snippet calls and output the result:


```
    [[Wayfinder?startId=`0` &level=`1` &sortBy=`menuindex` &sortOrder=`asc`]]
    
    [[Ditto?parents=0&display=5&tpl='@CODE:<li><a href="[+url+]" title="[+pagetitle+]">[+pagetitle+]</a> <span>[+date+]</span></li>']]
```

you would add the following to your Wordpress template:

```php
    <?php modxRunSnippet('Wayfinder', array('startId' => 0, 'level' => 1, 'sortBy' => 'menuindex', 'sortOrder' => 'asc')); ?>
    
    <?php modxRunSnippet('Ditto', array('parents' => 0, 'display' => 5, 'tpl' => '@CODE:<li><a href="[+url+]" title="[+pagetitle+]">[+pagetitle+]</a> <span>[+date+]</span></li>')); ?>
```

## Installation

Upload the plugin to your blog's `/wp-content/plugins`, Activate it, then start using the template functions in your templates.

## TODO

* The way the plugin is configured is pretty terrible.
* Make it auto-detect MODx Evo/Revo version and just work. Currently only works with MODx Evo, afaik.
* More testing.