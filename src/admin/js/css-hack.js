var previewTheme = 'preview-css';
var previewBasic = 'preview-basic';
var signUpBasic = 'sign-up-basic';
var previewValign = 'preview-valign';

if (!document.getElementById(previewTheme))
{   
    var head = document.getElementsByTagName('head')[0];
    var bootstrapWrapper = document.createElement('link');
    bootstrapWrapper.id = previewTheme;
    bootstrapWrapper.rel = 'stylesheet/less';
    bootstrapWrapper.type = 'text/css';
    bootstrapWrapper.href = '../wp-content/plugins/moosend/src/admin/css/ms4wp-preview.less';
    bootstrapWrapper.media = 'all';
    head.appendChild(bootstrapWrapper);
    
    var lessjs = document.createElement('script');
    lessjs.type = 'text/javascript';
    lessjs.src = '../wp-content/plugins/moosend/src/admin/dependencies/less-css/less.min.js';
    head.appendChild(lessjs);
}


if (!document.getElementById(previewBasic))
{  
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = previewBasic;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = '../wp-content/plugins/moosend/src/public/css/basic-theme.css';
    link.media = 'all';
    link.disabled = "disabled";
    head.appendChild(link);
}

if (!document.getElementById(previewValign))
{  
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = previewValign;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = '../wp-content/plugins/moosend/src/public/css/vertically-align.css';
    link.media = 'all';
    link.disabled = "disabled";
    head.appendChild(link);
}