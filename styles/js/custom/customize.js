var WEB_ROOT = WEB_ROOT || '';
var LOGINUID = LOGINUID || 0;
window.x_init_hook_validator = function() {
    jQuery('form.validator').each(function(){jQuery.fn.checkForm(this);});
};

window.x_init_hook_placehold = function() {
    //Fix IE下Bootstrap显示
    $('input').placeholder();

    //chosen 
    $(".chosen-select").chosen();
};


// 钱 -> 财务格式，保存的时候还需要去掉逗号
window.x_init_hook_currencyformat = function() {

      $('.money').formatCurrency({ symbol: '', colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: 2 });
      $('.money').blur(function() {
        $(this).formatCurrency({ symbol: '', colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: 2 });
      })
      .keyup(function(e) {
        var e = window.event || e;
        var keyUnicode = e.charCode || e.keyCode;
        if (e !== undefined) {
          switch (keyUnicode) {
            case 16: break; // Shift
            case 17: break; // Ctrl
            case 18: break; // Alt
            case 27: this.value = ''; break; // Esc: clear entry
            case 35: break; // End
            case 36: break; // Home
            case 37: break; // cursor left
            case 38: break; // cursor up
            case 39: break; // cursor right
            case 40: break; // cursor down
            case 78: break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
            case 110: break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
            case 190: break; // .
            default: $(this).formatCurrency({ colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: -1, eventOnDecimalsEntered: true });
          }
        }
      });

};


window.x_init_hook_click = function() {

    jQuery('a.ajaxlink').click(function() {
        if (jQuery(this).attr('no') == 'yes')
            return false;
        var link = jQuery(this).attr('href');
        var ask = jQuery(this).attr('ask');
        if (link.indexOf('/delete')>0 &&!confirm('确定删除本条记录吗？')) { 
            return false;
        } else if (ask && !confirm(ask)) {
            return false;
        }
        X.get(jQuery(this).attr('href'));
        return false;
    });
    jQuery('a.remove').click(function(){
        var u = jQuery(this).attr('href');
        if (confirm('确定删除该条记录吗？')){X.get(u);}
        return false;
    });


    jQuery('input[xtip$="."]').each(X.misc.inputblur);
    jQuery('input[xtip$="."]').focus(X.misc.inputclick);
    jQuery('input[xtip$="."]').blur(X.misc.inputblur);
};

/* X.misc Zone */
X.misc = {};
X.misc.copyToCB = function(tid) {
    var o = jQuery('#'+tid); o.select(); var maintext = o.val();
    if (window.clipboardData) {
        if ((window.clipboardData.setData("Text", maintext))) {
            var tip = o.attr('tip'); if ( tip ) alert(tip);
            return true;
        }
    }
    else if (window.netscape) {
        netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
        var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip) return;
        var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if (!trans) return;
        trans.addDataFlavor('text/unicode');
        var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext=maintext;
        str.data=copytext;
        trans.setTransferData("text/unicode",str,copytext.length*2);
        var clipid=Components.interfaces.nsIClipboard;
        if (!clip) return false;
        clip.setData(trans,null,clipid.kGlobalClipboard);
        var tip = o.attr('tip'); if ( tip ) alert(tip);
        return true;
    }
    return false;
};

X.misc.inputblur = function() {
    var v =jQuery(this).attr('value');
    var t =jQuery(this).attr('xtip');
    if( v == t || !v ) {
        jQuery(this).attr('value', t);
        jQuery(this).css('color', '#999');
    }
};
X.misc.inputclick = function() {
    var v =jQuery(this).attr('value');
    var t =jQuery(this).attr('xtip');
    if( v == t ) {
        jQuery(this).attr('value', '');
    }
    jQuery(this).css('color', '#333');
};

X.misc.multiclock = function(timeleft, counter) {
    var a = parseInt(jQuery("#"+timeleft).attr('diff'));
    if (!a>0) return;
    var b = (new Date()).getTime(); 
    var e = function() {
        var c = (new Date()).getTime();
        var ls = a + b - c;
        if ( ls > 0 ) {
            var ld = parseInt(ls/86400000) ; ls = ls % 86400000;
            var lh = parseInt(ls/3600000) ; ls = ls % 3600000;
            var lm = parseInt(ls/60000) ; 
            var ls = parseInt(Math.round(ls%60000)/1000);
            if (ld>0) {
                var html = '<li><span>'+ld+'</span>天</li><li><span>'+lh+'</span>小时</li><li><span>'+lm+'</span>分钟</li>';
            } else {
                var html = '<li><span>'+lh+'</span>小时</li><li><span>'+lm+'</span>分钟</li><li><span>'+ls+'</span>秒</li>';
            }
            jQuery("ul#"+counter).html(html);
        } else {
            jQuery("ul#"+counter).stopTime('"+counter');
            jQuery("ul#"+counter).html('end');
            window.location.reload();
        }
    };
    jQuery("ul#"+counter).everyTime(996, counter, e);
};

var editor_map = new Map();
var ueditor;
window.x_init_hook_editor = function() {
    if(!UE) return;

    jQuery('textarea.editor').each(function(index, e){
        ueditor = UE.getEditor(jQuery(e).prop('id'));
        editor_map.put(jQuery(e).prop('id'), ueditor);
    });
};

window.x_init_hook_select2 = function() {
    if(!UE) return;
    jQuery('select.select2').each(function(index, e){
        jQuery(e).select2();
    });
};

  //hack 原始Array，添加remove方法
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

/* 
 *  方法:Array.remove(dx) 
 *  功能:根据元素值删除数组元素. 
 *  参数:元素值 
 *  返回:在原数组上修改数组 
 *  作者：pxp 
 */  
Array.prototype.indexOf = function (val) {  
    for (var i = 0; i < this.length; i++) {  
        if (this[i] == val) {  
            return i;  
        }  
    }  
    return -1;  
};  
Array.prototype.removevalue = function (val) {  
    var index = this.indexOf(val);  
    if (index > -1) {  
        this.splice(index, 1);  
    }  
}; 