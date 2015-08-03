<?php

/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

define('UPLOAD_ROOT_PATH', "/uploads/");

if (!empty($_FILES)) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $year = date('Y'); $day = date('md');
    $relative_path = "/attachment/{$year}/{$day}/";
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . UPLOAD_ROOT_PATH . $relative_path;

    // 安全问题
    @mkdir($targetPath, 0777, true);
    // $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
    $file_extension =  pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION);
    $new_file_name = md5($_FILES['Filedata']['name'] . time()) . ".$file_extension";
    $targetFile =  str_replace('//','/',$targetPath) . $new_file_name;
    $reletiveTargetFile = str_replace('//','/',$relative_path) . $new_file_name;
    echo $reletiveTargetFile;
    move_uploaded_file($tempFile,iconv("UTF-8","gb2312", $targetFile)); 
}


?>