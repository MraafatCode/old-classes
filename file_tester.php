<?php

class MaestroValidate
{
    private $files_types = [
        'jpg'  => 'image/jpg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
    ];

    public $error_msg = "هذا الإمتداد غير مسموح به";
    private function check_if_there_rules($rules)
    {
        foreach ($rules as $rule) {
            if (!isset($this->files_types[$rule])) {
                return $rule;
            }
        }
    }

    public function add_extension($ext, $mime)
    {
        $this->files_types[$ext] = $mime;
    }

    private function return_extension($file)
    {
        print_r(getimagesize($file)); exit;
        $file_name = basename($file);
        $ext       = pathinfo($file_name, PATHINFO_EXTENSION);
        return $ext;

    }
    private function return_error($error_msg, $extension)
    {
        return $error_msg . ' ' . $extension;
    }
    private function type_validation($file, $rules)
    {
        $mimes_will_search_in = [];
        $error;
        foreach ($this->files_types as $key => $value) {
            foreach ($rules as $rule_key => $rule_value) {
                if ($key == $rule_value) {
                    array_push($mimes_will_search_in, $value);
                }
            }
        }
        $file_mime = mime_content_type($file);
        if (!in_array($file_mime, $mimes_will_search_in)) {
            $ext   = $this->return_extension($file);
            $error = $this->return_error($this->error_msg, $ext);
        }

        if (!empty($error)) {
            return $error;
        }
    }
    public function validate_file($file, $rules)
    {
        $rule_error = $this->check_if_there_rules($rules);
        if ($rule_error) {
            return "<h1>This file type $rule_error not available</h1>";
            exit;
        } else {
            $validate_errors = $this->type_validation($file, $rules);
            return $validate_errors;
        }
    }
}
// file path
$file = $_FILES["file"]["tmp_name"];
$p            = new MaestroValidate;
$p->error_msg = "Not Supported";
$p->add_extension('zip', 'application/zip');
$error = $p->validate_file($file, ['png', 'jpg', 'jpeg', 'zip']);
if($error){
    echo $error;
}else{
    echo "Ok";
}

