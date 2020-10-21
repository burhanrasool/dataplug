<?php
/**
 * Visual Form Builder
 * Created by 23rd and Walnut
 * www.23andwalnut.com
 */


$fb = new FormBuilder();
//$fb->action = isset($_GET['action'])?$_GET['action']:'';


if (!isset($_GET['action']))
{
    $fb->build();
}
else if ($_GET['action'] == 'download')
{
    $fb->download_zip();
}
else if ($_GET['action'] == 'manual')
{
    $fb->manual_download($_GET['directory']);
}


class FormBuilder
{
    public $output_directory;
    public $default_width;
    public $rules;
    public $form_html;
    public $form_width;
    public $form_directory;
    public $output_method;
    public $file_names;
    public $theme;





    function __construct()
    {

        $this->output_directory = rtrim(dirname(__FILE__), 'application') . 'your_forms';
        //$this->resp_directory = rtrim(dirname(__FILE__), 'application') . 'your_forms';

        $this->default_width = 500;
        $this->rules = isset($_POST['rules']) ? $_POST['rules'] : '';
        $this->form_html = isset($_POST['form_html']) ? $_POST['form_html'] : false;
        $this->form_width = (isset($_POST['width']) ? $_POST['width'] : $this->default_width) . 'px';
        $this->theme = isset($_POST['theme']) ? $_POST['theme'] : 'default';

        //The directory of the the theme specific resources
        $this->theme_resources_directory = 'form_resources/themes/' . $this->theme . '/';

        //Output names structure for created form/zip
        $this->file_names = array(
            'html' => 'index.html',
            'css' => 'css/style.css',
            'js' => 'js/main.js',
            'php' => 'process_form.php',
            'tools-js' => 'js/jquery.tools.js',
            'uniform-js' => 'js/jquery.uniform.min.js',
            'uniform-css' => 'css/uniform.css'
        );
    }





    function build()
    {


        if (!$this->form_html)
            die('There is no data to process');

        $this->form_directory = $form_directory = time();

        //Set up the directory structure for the new form
        if (!mkdir("$this->output_directory/$form_directory", 0777, true))
            die('Unable to create a new folder for the form. Please check write permissions on the \'your_forms\' folder. They should be 777.');

        mkdir("$this->output_directory/$form_directory/js", 0777);
        mkdir("$this->output_directory/$form_directory/css", 0777);

        //prepare the form
        $form_template = file_get_contents('form_resources/form_template.php');
        $this->form_html = stripslashes(str_replace('${form}', $this->form_html, $form_template));


        //write form to file
        $fp = fopen("$this->output_directory/$form_directory/" . $this->file_names['html'], 'w');
        fwrite($fp, $this->form_html);
        fclose($fp);

        //prepare the css
        $form_width_css = "\n\n.TTWForm{\n \twidth: $this->form_width;\n}\n";

        $css_template = file_get_contents($this->theme_resources_directory . 'css/style.css');
        $form_css = stripslashes(str_replace('${form_width}', $form_width_css, $css_template));


        //write the css to the file
        $fp = fopen("$this->output_directory/$form_directory/" . $this->file_names['css'], 'w');
        fwrite($fp, $form_css);
        fclose($fp);


        //prepare the server side validation script
        $this->rules = $this->format_rules();
        $this->rules = $this->print_array($this->rules);

        $sss_template = file_get_contents('form_resources/process_form_template.php');
        $sss = str_replace('${rules}', $this->rules, $sss_template);

        //write server side script to file
        $fp = fopen("$this->output_directory/$form_directory/" . $this->file_names['php'], 'w');
        fwrite($fp, $sss);
        fclose($fp);


        //Handle Date and Range Inputs
        $dates_ranges = $_POST['dates_ranges'];

        $date_ids = !empty($dates_ranges['date']) ? '#' . trim(implode(', #', explode(' ', trim($dates_ranges['date']))), ',') : null;
        $range_ids = !empty($dates_ranges['range']) ? '#' . trim(implode(', #', explode(' ', trim($dates_ranges['range']))), ',') : null;

        $date_js = isset($date_ids) ? '$("' . $date_ids . '").dateinput();' : null;
        $range_js = isset($range_ids) ? '$("' . $range_ids . '").rangeinput();' : null;

        $date_range_js = (isset($date_js) && isset($range_js)) ? "\n\t" . $date_js . "\n\n\t" . $range_js . "\n" : "\n\t" . $date_js . $range_js;
        $date_range_js = (!empty($date_range_js) ? "//Date and Range Inputs" : '') . $date_range_js;


        //copy form dependencies
        $js_template = file_get_contents('form_resources/js/main.js');
        $js = stripslashes(str_replace('${date_range_inputs}', $date_range_js, $js_template));


        //write js script to file
        $fp = fopen("$this->output_directory/$form_directory/" . $this->file_names['js'], 'w');
        fwrite($fp, $js);
        fclose($fp);

        if ($_POST['output_type'] == 'code')
        {
            $this->display_form_code();
        }
        else
        {
            if (!isset($_GET['action']))
                $this->build_zip();
            else $this->download_zip();
        }
    }





    function display_form_code()
    {
        $directory = $this->output_directory . '/' . $this->form_directory . '/';

        $form['html'] = stripslashes(file_get_contents($directory . $this->file_names['html']));
        $form['css'] = stripslashes(file_get_contents($directory . $this->file_names['css']));
        $form['js'] = stripslashes(file_get_contents($directory . $this->file_names['js']));
        $form['php'] = file_get_contents($directory . $this->file_names['php']);

        $this->delete_form($this->form_directory);

        echo json_encode($form);
    }





    function build_zip()
    {
        if (extension_loaded('zip'))
        {
            $zip = new ZipArchive();
            $zip_directory = $this->output_directory . '/' . $this->form_directory . '/';
            $zip_name = $zip_directory . $this->form_directory . '.zip';

            if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE)
            {
                exit("cannot open <$zip_name>\n");
            }

            $zip->addFile($zip_directory . $this->file_names['html'], $this->file_names['html']);
            $zip->addFile($zip_directory . $this->file_names['css'], $this->file_names['css']);
            $zip->addFile($zip_directory . $this->file_names['js'], $this->file_names['js']);
            $zip->addFile($zip_directory . $this->file_names['php'], $this->file_names['php']);

            //Don't forget about the Uniform plugin
            //These aren't being copied to the "form directory" folder because they are static. Just copy them from the
            //resources folder
            $zip->addFile('form_resources/js/jquery.tools.js', $this->file_names['tools-js']);
            $zip->addFile('form_resources/js/jquery.uniform.min.js', $this->file_names['uniform-js']);
            $zip->addFile($this->theme_resources_directory . 'css/uniform.' . $this->theme . '.css', $this->file_names['uniform-css']);


            foreach (glob($this->theme_resources_directory . 'images/*') as $filename)
            {
                $zip->addFile($filename, 'images/' . end(explode('/', $filename)));
            }
            $zip->close();

            echo json_encode(array('zip' => $this->form_directory));
        }
        else
        {
            //Since the zip isn't being created, add the non template files to the directory
            $this->get_non_template_files($this->form_directory);

            echo json_encode(array(
                'error' => 'NO-ZIP',
                'form_path' => 'your_forms/' . $this->form_directory . '/',
                'manual_download' => $this->manual_download($this->form_directory)
            ));
        }
    }





    function download_zip()
    {
        $zip_directory = $this->output_directory . '/' . $_GET['form'] . '/';
        $zip_name = $_GET['form'] . '.zip';
        $path = $zip_directory . $zip_name;

        if (file_exists($path))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            flush();
            readfile($path);

            $this->delete_form($_GET['form']);
            exit;
        }
        else
        {
            exit('Error processing request. The file ' . $path . ' does not exist.');
        }
    }





    //If the zip extension is not installed, then we will need to copy the non template files to the new form's directory
    function get_non_template_files($directory)
    {
        $directory = $this->output_directory . '\\' . $directory . '\\';

        copy('form_resources/js/jquery.tools.js', $directory . $this->file_names['tools-js']);
        copy('form_resources/js/jquery.uniform.min.js', $directory . $this->file_names['uniform-js']);
        copy($this->theme_resources_directory . 'css/uniform.' . $this->theme . '.css', $directory . $this->file_names['uniform-css']);

        if (!mkdir("$directory/images/"))
            exit('Error creating images directory');

        foreach (glob($this->theme_resources_directory . 'images\*') as $filename)
        {
            copy($filename, $directory . 'images/' . end(explode('\\', $filename)));
        }
    }




    function format_rules()
    {
        $field_rules = $this->rules;

        $formatted_rules = '';

        if (is_array($field_rules))
        {
            foreach ($field_rules as $field => $rules)
            {
                $formatted_rules[$field] = '';
                //           echo $field;
                foreach ($rules as $rule => $value)
                {
                    if ($rule == 'required')
                        $formatted_rules[$field] .= 'required|';
                    else if ($rule == 'type')
                    {
                        if ($value == 'email' || $value == 'number')
                            $formatted_rules[$field] .= $value . '|';
                    }
                    else
                    {
                        $formatted_rules[$field] .= $rule . '[' . $value . ']|';
                    }
                }
                $formatted_rules[$field] = rtrim($formatted_rules[$field], '|');
            }
        }

        return $formatted_rules;
    }





    function print_array($array, $indent = 1)
    {
        if (!is_array($array))
            return false;

        $array_string = "array(\n";

        foreach ($array as $key => $value)
            $array_string .= str_repeat("\t", $indent) . "'$key'=>'$value',\n";

        $array_string = trim($array_string, ",\n");

        $array_string .= "\n" . str_repeat("\t", $indent - 1) . ")";

        return $array_string;
    }





    function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..'))
            {
                if (is_dir($src . '/' . $file))
                {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else
                {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }





    function delete_form($directory)
    {
        $form_directory = $this->output_directory . '/' . $directory . '/';


        foreach ($this->file_names as $file_name)
        {
            $path = $form_directory . $file_name;
            if (file_exists($path))
                @unlink($path);
        }

        if (file_exists($form_directory . $directory . '.zip'))
            @unlink($form_directory . $directory . '.zip');

        @rmdir($form_directory . 'js');
        @rmdir($form_directory . 'css');

        @rmdir($form_directory);
    }





    function manual_download($handle)
    {
        if (opendir($this->output_directory . '/' . $handle))
        {
            $links = "<div id='manual-download'>Files (click save as):<br/><br/>";

            $form_files = $this->getDirectoryTree('../your_forms/' . $handle);

            $links .= $form_files['index.html'];
            $links .= $form_files['process_form.php'];

            $links .= "<h5>css</h5>";
            $links = $this->print_folder($form_files['css'], $links);

            $links .= "<h5>images</h5>";
            $links = $this->print_folder($form_files['images'], $links);

            $links .= "<h5>js</h5>";
            $links = $this->print_folder($form_files['js'], $links);

            $links .= '</div>';

            return $links;
        }
    }





    function print_folder($folder,  $links )
    {
        foreach($folder as $file)
             $links .= $file;

        return $links;
    }

    function getDirectoryTree($outerDir)
    {
        $dirs = array_diff(scandir($outerDir), Array(".", ".."));
        $dir_array = Array();
        foreach ($dirs as $d)
        {
            if (is_dir($outerDir . "/" . $d)) $dir_array[$d] = $this->getDirectoryTree($outerDir . "/" . $d);
            else $dir_array[$d] = "<a href='" . trim($outerDir."/".$d, '.,/') . "' target='_blank'>$d</a>";
        }
        return $dir_array;
    }





    function pre($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

?>