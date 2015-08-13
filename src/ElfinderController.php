<?php namespace Barryvdh\Elfinder;

use Barryvdh\Elfinder\Support\BaseController;

class ElfinderController extends BaseController
{
    protected $package = 'laravel-elfinder';

    public function showIndex()
    {
        return $this->app['view']
            ->make($this->package . '::elfinder')
            ->with($this->getViewVars());
    }

    public function showTinyMCE()
    {
        return $this->app['view']
            ->make($this->package . '::tinymce')
            ->with($this->getViewVars());
    }

    public function showTinyMCE4()
    {
        return $this->app['view']
            ->make($this->package . '::tinymce4')
            ->with($this->getViewVars());
    }

    public function showCKeditor4()
    {
        return $this->app['view']
            ->make($this->package . '::ckeditor4')
            ->with($this->getViewVars());
    }

    public function showPopup($input_id)
    {
        return $this->app['view']
            ->make($this->package . '::standalonepopup')
            ->with($this->getViewVars())
            ->with(compact('input_id'));
    }

    public function showConnector()
    {
        $ds = DIRECTORY_SEPARATOR;
        $pp = public_path().$ds;
        $user       = \Auth::user();
        $dir        = \Config::get($this->package . '::dir');
        $files_dir  = \Config::get($this->package . '::files_dir');
        $elpath     = \Config::get($this->package . '::elpath');
        $roots      = \Config::get($this->package . '::roots');
        $dir_1 = $dir.$ds.$user->id;
        if(!\File::exists($pp.$dir_1))
            \File::makeDirectory($pp.$dir_1);
        $dir_2 = $dir_1.$ds.$files_dir;
        if(!\File::exists($pp.$dir_2))
            \File::makeDirectory($pp.$dir_2);
        $dir = $dir_2;
        if (!$roots)
        {
            $roots = array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path() . DIRECTORY_SEPARATOR . $dir, // path to files (REQUIRED)
                    'URL' => asset($dir), // URL to files (REQUIRED)
                    'accessControl' => \Config::get($this->package . '::access') // filter callback (OPTIONAL)
                )
            );
        }
        $opts = array(
            'roots' => $roots
        );
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    protected function getViewVars()
    {
        $dir = 'packages/barryvdh/' . $this->package;
        $locale = $this->app->config->get('app.locale');
        if (!file_exists($this->app['path.public'] . "/$dir/js/i18n/elfinder.$locale.js")) {
            $locale = false;
        }
        $csrf = true;
        return compact('dir', 'locale', 'csrf');
    }
}
