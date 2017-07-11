<?php

define('IN_PHPMPS', true);
class php_editor {

    var $id = '';
    var $name = '';
    var $width = '100%';
    var $height = '300px';
    var $css = '';
    var $content = '';
    var $item = 'basic';
    var $upimage = false;

    var $items = array();

    function __construct($name) {
        $this->name = $name;
        $this->id = $name;
    }

    function php_editor($name) {
        $this->__construct($name);
    }
		
    function create_html() {
        $this->_init_item();
        $content = '';
        $content .= '<script charset="gb2312" src="../include/editor/kindeditor.js"></script>'."\r\n";
	    $content .= '<script charset="gb2312" src="./include/editor/lang/zh_CN.js"></script>'."\r\n";
        $content .= "<script type=\"text/javascript\">\r\n";
		$content .= "\tvar editor; \r\n";
		$content .= "\tKindEditor.ready(function(K)  { \r\n";
		$content .= "\teditor = K.create('textarea[name=\"content\"]', { \r\n";
		$content .= "\tcssPath : '../include/editor/plugins/code/prettify.css',\r\n";
		//$content .= "\tuploadJson : '../include/editor/php/upload.php',\r\n";
		$content .= "\tallowFileManager : true, \r\n";
        $this->css && $content .= "\t\cssPath : '$this->css', \r\n";
        $this->item != 'default' && $content .= "\t\titems : {$this->items[$this->item]}";
        $content .= "\tafterCreate : function() {\r\n";
        $content .= "\tvar self = this;\r\n";
        $content .= "\tK.ctrl(document, 13, function() {\r\n";
        $content .= "\tself.sync();\r\n";
        $content .= "\tK('form[name=example]')[0].submit();\r\n";
        $content .= "\t});\r\n";
        $content .= "\tK.ctrl(self.edit.doc, 13, function() {\r\n";
        $content .= "\tself.sync();";
        $content .= "\tK('form[name=example]')[0].submit();\r\n";
        $content .= "\t});\r\n";
        $content .= "\t},afterBlur: function(){this.sync();}\r\n";
        $content .= "\t});\r\n";
        $content .= "\tprettyPrint();\r\n";
	    $content .= "\t});\r\n";
        $content .= "</script>\r\n";
        $content .= "<textarea id=\"$this->id\" name=\"$this->name\" style=\"width:$this->width;height:$this->height;visibility:hidden;\">$this->content</textarea>";

        return $content;
    }

    function create_html2($id, $name, $content='', $item='default', $width='100%', $height='300px',$loadjs = false) {
        $this->_init_item();
        $content = '';
        $loadjs && $content .= '<script type="text/javascript" charset="utf-8" src="include/phpdo_e/kindeditor.js"></script>'."\r\n";
        $content .= "<script type=\"text/javascript\">\r\n";
        $content .= "\tKE.show({ \r\n";
        $content .= "\t\tid : '$id', \r\n";
        $item != 'default' && $content .= "\t\titems : {$this->items[$item]} \r\n";
        $content .= "\t});\r\n";
        $content .= "</script>\r\n";
        $content .= "<textarea id=\"$id\" name=\"$name\" style=\"width:$width;height:$height;visibility:hidden;\">$content</textarea>";

        return $content;
    }

    function _init_item() {
        $image = $this->upimage ? ", 'image'" : '';

        $this->items['default'] = "
            ['source', '|', 'undo', 'redo', '|', 'print', 'template', 'cut', 'copy', 'paste',
		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		'superscript', 'clearhtml', 'quickformat', 'selectall', '|','preview', '/',
		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
		'flash', 'media', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		'anchor', 'link', 'unlink' ],
        ";
        $this->items['admin'] = $this->items['default'];
        $this->items['basic'] = "['source', 'undo', 'redo', 'formatblock', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', 'hr', 'link', 'unlink' ],
        ";
        $this->items['vip1'] = "['source', '|', 'undo', 'redo', '|', 'print', 'template', 'cut', 'copy', 'paste',
		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		'superscript', 'clearhtml', 'quickformat', 'selectall', '|','preview', '/',
		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
		'flash', 'media', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		'anchor', 'link', 'unlink' ],
        ";
    }

}
?>