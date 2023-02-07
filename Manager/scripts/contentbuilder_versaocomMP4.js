var cb_list = '';
var cb_edit = true;
var oScripts = document.getElementsByTagName("script");
var sScriptPath;
for (var i = 0; i < oScripts.length; i++) {
    var sSrc = oScripts[i].src.toLowerCase();
    if (sSrc.indexOf("contentbuilder-src.js") != -1) sScriptPath = oScripts[i].src.replace(/contentbuilder-src.js/, "");
    if (sSrc.indexOf("contentbuilder.js") != -1) sScriptPath = oScripts[i].src.replace(/contentbuilder.js/, "")
}

if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
    var sc = document.createElement('script');
    sc.src = sScriptPath + 'megapix-image.js';
    document.getElementsByTagName('head')[0].appendChild(sc)
}(function (jQuery) {
    var $activeRow;
    jQuery.contentbuilder = function (element, options) {
        var defaults = {
            zoom: '1',
            selectable: "h1,h2,h3,h4,h5,h6,p,ul,ol,small,.edit",
            editMode: 'default',
            onRender: function () {},
            onDrop: function () {},
            snippetFile: 'assets/default/snippets.html',
            hiquality: true,
            snippetTool: 'right',
            snippetOpen: false,
            imageselect: '',
            imageEmbed: true,
            sourceEditor: true,
            fileselect: '',
            enableZoom: true,
            colors: ["#ffffc5", "#e9d4a7", "#ffd5d5", "#ffd4df", "#c5efff", "#b4fdff", "#c6f5c6", "#fcd1fe", "#ececec", "#f7e97a", "#d09f5e", "#ff8d8d", "#ff80aa", "#63d3ff", "#7eeaed", "#94dd95", "#ef97f3", "#d4d4d4", "#fed229", "#cc7f18", "#ff0e0e", "#fa4273", "#00b8ff", "#0edce2", "#35d037", "#d24fd7", "#888888", "#ff9c26", "#955705", "#c31313", "#f51f58", "#1b83df", "#0bbfc5", "#1aa71b", "#ae19b4", "#333333"],
            snippetList: '#divSnippetList'
        };
        this.settings = {};
        var $element = jQuery(element),
            element = element;
        this.init = function () {
            this.settings = jQuery.extend({}, defaults, options);
            if (!this.settings.enableZoom) {
                localStorage.removeItem("zoom")
            }
            if (localStorage.getItem("zoom") != null) {
                this.settings.zoom = localStorage.zoom
            } else {
                localStorage.zoom = this.settings.zoom
            }
            $element.css('zoom', this.settings.zoom);
            $element.css('-moz-transform', 'scale(' + this.settings.zoom + ')');
            $element.addClass('connectSortable');
            this.settings.zoom = this.settings.zoom + '';
            if (this.settings.zoom.indexOf('%') != -1) {
                this.settings.zoom = this.settings.zoom.replace('%', '') / 100;
                localStorage.zoom = this.settings.zoom
            }
            if (this.settings.zoom == 'NaN') {
                this.settings.zoom = 1;
                localStorage.zoom = 1
            }
            if (cb_list == '') {
                cb_list = '#' + $element.attr('id')
            } else {
                cb_list = cb_list + ',#' + $element.attr('id')
            }
            $element.css({
                'min-height': '200px'
            });
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>')
            }
            if (jQuery('#divSnippets').length == 0) {
                jQuery('#divCb').append('<div id="divSnippets" style="display:none"></div>');
                var s = '<div id="divTool"><div id="divSnippetList"></div>';
                s += '';
                s += '<br>Buscar: <input type="text" id="busca_elemento" style="width:85px;height:25px;margin-right:5px;"><input type="button" id="busca_elemento" class="fb_button" onclick="ShowElementos();" value="Ok" /><!--<br><br><div id="divRange"><input type="range" id="inpZoom" min="80" max="100" value="100"></div>-->';
                s += '';
                s += '<a id="lnkToolOpen" href="#"><i class="cb-icon-pencil"></i></a></div>';
                jQuery('#divCb').append(s);
                jQuery('#inpZoom').val(this.settings.zoom * 100);
                jQuery('#divCb input[type="range"]').rangeslider({
                    onSlide: function (position, value) {},
                    polyfill: false
                });
                var val = jQuery('#inpZoom').val() / 100;
                this.zoom(val);
                jQuery('#inpZoom').on('change', function () {
                    var val = jQuery('#inpZoom').val() / 100;
                    $element.data('contentbuilder').zoom(val)
                });
                if (!this.settings.enableZoom && this.settings.snippetList == '#divSnippetList') {
                    jQuery('#divRange').css('display', 'none');
                    jQuery('#divSnippetList').css('height', '100%')
                }
                jQuery.get(this.settings.snippetFile, function (data) {
                    var htmlData = '';
                    var htmlThumbs = '';
                    var i = 1;
                    jQuery('<div/>').html(data).children('div').each(function () {
                        var block = jQuery(this).html();
                        var blockEncoded = jQuery('<div/>').text(block).html();
                        htmlData += '<div id="snip' + i + '">' + blockEncoded + '</div>';
                        htmlThumbs += '<div id="el' + i + '" title="' + titulo[i-1] + '" data-snip="' + i + '"><img src="' + jQuery(this).data("thumb") + '" /></div>';
                        i++
                    });
                    jQuery('#divSnippets').html(htmlData);
                    jQuery($element.data('contentbuilder').settings.snippetList).html(htmlThumbs);
                    jQuery($element.data('contentbuilder').settings.snippetList + ' > div').draggable({
                        cursor: 'move',
                        helper: function () {
                            return jQuery("<div class='dynamic'></div>")[0]
                        },
                        connectToSortable: cb_list,
                        stop: function (event, ui) {
                            $element.children("div").each(function () {
                                if (jQuery(this).children("img").length == 1) {
                                    jQuery(this).remove()
                                }
                            })
                        }
                    })
                })
            }
            $element.children("*").not('link').wrap("<div class='ui-draggable'></div>");
            $element.children("*").append('<div class="row-tool">' + '<div class="row-handle"><i class="cb-icon-move"></i></div>' + '<div class="row-html"><i class="cb-icon-code"></i></div>' + '<div class="row-copy"><i class="cb-icon-plus"></i></div>' + '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' + '</div>');
            if (jQuery('#temp-contentbuilder').length == 0) {
                jQuery('#divCb').append('<div id="temp-contentbuilder" style="display: none"></div>')
            }
            var $window = jQuery(window);
            var windowsize = $window.width();
            var toolwidth = 260;
            if (windowsize < 600) {
                toolwidth = 150
            }
            if (this.settings.snippetTool == 'right') {
                jQuery('#divTool').css('width', toolwidth + 'px');
                jQuery('#divTool').css('right', '-' + toolwidth + 'px');
                jQuery("#lnkToolOpen").unbind('click');
                jQuery("#lnkToolOpen").click(function (e) {
                    $element.data('contentbuilder').clearControls();
                    if (parseInt(jQuery('#divTool').css('right')) == 0) {
                        jQuery('#divTool').animate({
                            right: '-=' + toolwidth + 'px'
                        }, 200)
                    } else {
                        jQuery('#divTool').animate({
                            right: '+=' + toolwidth + 'px'
                        }, 200)
                    }
                    e.preventDefault()
                });
                jQuery('.row-tool').css('right', 'auto');
                if (windowsize < 600) {
                    jQuery('.row-tool').css('left', '-30px')
                } else {
                    jQuery('.row-tool').css('left', '-37px')
                }
                if (this.settings.snippetOpen) {
                    jQuery('#divTool').animate({
                        right: '+=' + toolwidth + 'px'
                    }, 900)
                }
            } else {
                jQuery('#divTool').css('width', toolwidth + 'px');
                jQuery('#divTool').css('left', '-' + toolwidth + 'px');
                jQuery('#lnkToolOpen').addClass('leftside');
                jQuery("#lnkToolOpen").unbind('click');
                jQuery("#lnkToolOpen").click(function (e) {
                    $element.data('contentbuilder').clearControls();
                    if (parseInt(jQuery('#divTool').css('left')) == 0) {
                        jQuery('#divTool').animate({
                            left: '-=' + (toolwidth + 0) + 'px'
                        }, 200)
                    } else {
                        jQuery('#divTool').animate({
                            left: '+=' + (toolwidth + 0) + 'px'
                        }, 200)
                    }
                    e.preventDefault()
                });
                jQuery('.row-tool').css('left', 'auto');
                if (windowsize < 600) {
                    jQuery('.row-tool').css('right', '-30px')
                } else {
                    jQuery('.row-tool').css('right', '-37px')
                }
                if (this.settings.snippetOpen) {
                    jQuery('#divTool').animate({
                        left: '+=' + toolwidth + 'px'
                    }, 900)
                }
            }
            this.applyBehavior();
            this.settings.onRender();
            $element.sortable({
                items: '.ui-draggable',
                connectWith: '.connectSortable',
                'distance': 5,
                axis: 'y',
                tolerance: 'pointer',
                handle: '.row-handle',
                delay: 200,
                cursor: 'move',
                placeholder: 'block-placeholder',
                start: function (e, ui) {
                    jQuery(ui.placeholder).slideUp(80);
                    cb_edit = false
                },
                change: function (e, ui) {
                    jQuery(ui.placeholder).hide().slideDown(80)
                },
                deactivate: function (event, ui) {
                    cb_edit = true;
                    var bDrop = false;
                    if (ui.item.find('.row-tool').length == 0) {
                        bDrop = true
                    }
                    if (ui.item.parent().attr('id') == $element.attr('id')) {
                        ui.item.replaceWith(ui.item.html());
                        $element.children("*").each(function () {
                            if (!jQuery(this).hasClass('ui-draggable')) {
                                jQuery(this).wrap("<div class='ui-draggable'></div>")
                            }
                        });
                        $element.children('.ui-draggable').each(function () {
                            if (jQuery(this).find('.row-tool').length == 0) {
                                jQuery(this).append('<div class="row-tool">' + '<div class="row-handle"><i class="cb-icon-move"></i></div>' + '<div class="row-html"><i class="cb-icon-code"></i></div>' + '<div class="row-copy"><i class="cb-icon-plus"></i></div>' + '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' + '</div>')
                            }
                        });
                        $element.children('.ui-draggable').each(function () {
                            if (jQuery(this).children('*').length == 1) {
                                jQuery(this).remove()
                            }
                        })
                    }
                    $element.data('contentbuilder').applyBehavior();
                    $element.data('contentbuilder').settings.onRender();
                    if (bDrop) $element.data('contentbuilder').settings.onDrop(event, ui)
                }
            });
            if (cb_list.indexOf(',') != -1) {
                jQuery(cb_list).sortable('option', 'axis', false)
            }
            jQuery.ui.isOverAxis = function (x, reference, size) {
                return (x >= reference) && (x <= (reference + size))
            };
            $element.droppable({
                drop: function (event, ui) {
                    if (jQuery(ui.draggable).data('snip')) {
                        var snip = jQuery(ui.draggable).data('snip');
                        var snipHtml = jQuery('#snip' + snip).text();
                        jQuery(ui.draggable).data('snip', null);
                        return ui.draggable.html(snipHtml);
                        event.preventDefault()
                    }
                },
                tolerance: 'pointer',
                greedy: true
            });
            jQuery(document).bind('mousedown', function (event) {
                if (jQuery(event.target).attr("class") == 'ovl') {
                    jQuery(event.target).css('z-index', '-1')
                }
                if (jQuery(event.target).parents('.ui-draggable').length > 0 && jQuery(event.target).parents(cb_list).length > 0) {
                    var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                    jQuery(".ui-draggable").removeClass('code');
                    if (jQuery(event.target).parents("[data-mode='code']").length > 0) {
                        jQuery(event.target).parents(".ui-draggable").addClass('code')
                    }
                    jQuery(".ui-draggable").removeClass('ui-dragbox-outlined');
                    jQuery(event.target).parents(".ui-draggable").addClass('ui-dragbox-outlined');
                    if (is_firefox) jQuery(event.target).parents(".ui-draggable").addClass('firefox');
                    jQuery('.row-tool').stop(true, true).fadeOut(0);
                    if (jQuery(event.target).parents(".ui-draggable").find("[data-html-edit='off']").length > 0 || !$element.data('contentbuilder').settings.sourceEditor) {
                        jQuery(event.target).parents(".ui-draggable").find('.row-tool .row-html').css({
                            display: 'none'
                        })
                    }
                    jQuery(event.target).parents(".ui-draggable").find('.row-tool').stop(true, true).css({
                        display: 'none'
                    }).fadeIn(300);
                    return
                }
                if (jQuery(event.target).is('[contenteditable]') || jQuery(event.target).css('position') == 'absolute' || jQuery(event.target).css('position') == 'fixed') {
                    return
                }
                jQuery(event.target).parents().each(function (e) {
                    if (jQuery(this).is('[contenteditable]') || jQuery(this).css('position') == 'absolute' || jQuery(this).css('position') == 'fixed') {
                        return
                    }
                });
                $element.data('contentbuilder').clearControls()
            })
        };
        this.html = function () {
            var selectable = this.settings.selectable;
            jQuery('#temp-contentbuilder').html($element.html());
            jQuery('#temp-contentbuilder').find('.row-tool').remove();
            jQuery('#temp-contentbuilder').find('.ovl').remove();
            jQuery('#temp-contentbuilder').find('[contenteditable]').removeAttr('contenteditable');
            jQuery('*[class=""]').removeAttr('class');
            jQuery('#temp-contentbuilder').find('.ui-draggable').replaceWith(function () {
                return jQuery(this).html()
            });
            jQuery('#temp-contentbuilder').find('p').each(function () {
                if (jQuery.trim(jQuery(this).text()) == '') jQuery(this).remove()
            });
            jQuery("#temp-contentbuilder").find("[data-mode='code']").each(function () {
                jQuery(this).html("")
            });
            var html = jQuery('#temp-contentbuilder').html().trim();
            html = html.replace(/<font/g, '<span').replace(/<\/font/g, '</span');
            return html
        };
        this.zoom = function (n) {
            this.settings.zoom = n;
            jQuery(cb_list).css('zoom', n);
            jQuery(cb_list).css('-moz-transform', 'scale(' + n + ')');
            localStorage.zoom = n;
            this.clearControls()
        };
        this.clearControls = function () {
            jQuery('.row-tool').stop(true, true).fadeOut(0);
            jQuery(".ui-draggable").removeClass('code');
            jQuery(".ui-draggable").removeClass('ui-dragbox-outlined');
            var selectable = this.settings.selectable;
            $element.find(selectable).blur()
        };
        this.viewHtml = function () {
            jQuery('#md-html').css('width', '45%');
            jQuery('#md-html').simplemodal();
            jQuery('#md-html').data('simplemodal').show();
            jQuery('#txtHtml').val(this.html());
            jQuery('#btnHtmlOk').unbind('click');
            jQuery('#btnHtmlOk').bind('click', function (e) {
                $element.html(jQuery('#txtHtml').val());
                jQuery('#md-html').data('simplemodal').hide();
                $element.children("*").wrap("<div class='ui-draggable'></div>");
                $element.children("*").append('<div class="row-tool">' + '<div class="row-handle"><i class="cb-icon-move"></i></div>' + '<div class="row-html"><i class="cb-icon-code"></i></div>' + '<div class="row-copy"><i class="cb-icon-plus"></i></div>' + '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' + '</div>');
                $element.data('contentbuilder').applyBehavior();
                $element.data('contentbuilder').settings.onRender()
            })
        };
        this.loadHTML = function (html) {
            $element.html(html);
            $element.children("*").wrap("<div class='ui-draggable'></div>");
            $element.children("*").append('<div class="row-tool">' + '<div class="row-handle"><i class="cb-icon-move"></i></div>' + '<div class="row-html"><i class="cb-icon-code"></i></div>' + '<div class="row-copy"><i class="cb-icon-plus"></i></div>' + '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' + '</div>');
            $element.data('contentbuilder').applyBehavior();
            $element.data('contentbuilder').settings.onRender()
        };
        this.applyBehavior = function () {
            $element.find('a').click(function () {
                return false
            });
            $element.find("[data-mode='code']").each(function () {
                jQuery(this).html(decodeURIComponent(jQuery(this).attr("data-html")))
            });
            var selectable = this.settings.selectable;
            var hq = this.settings.hiquality;
            var imageEmbed = this.settings.imageEmbed;
            var colors = this.settings.colors;
            var editMode = this.settings.editMode;
            var imageselect = this.settings.imageselect;
            var fileselect = this.settings.fileselect;
            $element.contenteditor({
                fileselect: fileselect,
                editable: selectable,
                colors: colors,
                editMode: editMode
            });
            $element.data('contenteditor').render();
            $element.find('img').each(function () {
                if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                jQuery(this).imageembed({
                    hiquality: hq,
                    imageselect: imageselect,
                    fileselect: fileselect,
                    imageEmbed: imageEmbed
                });
                if (jQuery(this).parents('figure').length != 0) {
                    if (jQuery(this).parents('figure').find('figcaption').css('position') == 'absolute') {
                        jQuery(this).parents('figure').imageembed({
                            hiquality: hq,
                            imageselect: imageselect,
                            fileselect: fileselect,
                            imageEmbed: imageEmbed
                        })
                    }
                }
            });
            $element.find(".embed-responsive").each(function () {
                if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                if (jQuery(this).find('.ovl').length == 0) {
                    jQuery(this).append('<div class="ovl" style="position:absolute;background:#fff;opacity:0.2;cursor:pointer;top:0;left:0px;width:100%;height:100%;z-index:-1"></div>')
                }
            });
            $element.find(".embed-responsive").hover(function () {
                if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                if (jQuery(this).parents(".ui-draggable").css('outline-style') == 'none') {
                    jQuery(this).find('.ovl').css('z-index', '1')
                }
            }, function () {
                jQuery(this).find('.ovl').css('z-index', '-1')
            });
            $element.find(selectable).unbind('focus');
            $element.find(selectable).focus(function () {
                var zoom = $element.data('contentbuilder').settings.zoom;
                var selectable = $element.data('contentbuilder').settings.selectable;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                jQuery(".ui-draggable").removeClass('code');
                if (jQuery(this).parents("[data-mode='code']").length > 0) {
                    jQuery(this).parents(".ui-draggable").addClass('code')
                }
                jQuery(".ui-draggable").removeClass('ui-dragbox-outlined');
                jQuery(this).parents(".ui-draggable").addClass('ui-dragbox-outlined');
                if (is_firefox) jQuery(this).parents(".ui-draggable").addClass('firefox');
                jQuery('.row-tool').stop(true, true).fadeOut(0);
                if (jQuery(this).parents(".ui-draggable").find("[data-html-edit='off']").length > 0 || !$element.data('contentbuilder').settings.sourceEditor) {
                    jQuery(this).parents(".ui-draggable").find('.row-tool .row-html').css({
                        display: 'none'
                    })
                }
                jQuery(this).parents(".ui-draggable").find('.row-tool').stop(true, true).css({
                    display: 'none'
                }).fadeIn(300)
            });
            $element.children("div").find('.row-remove').unbind();
            $element.children("div").find('.row-remove').click(function () {
                jQuery(this).parents('.ui-draggable').fadeOut(400, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery("#divRteLink").stop(true, true).fadeOut(0);
                    jQuery("#divFrameLink").stop(true, true).fadeOut(0);
                    jQuery(this).remove();
                    $element.data('contentbuilder').settings.onRender()
                })
            });
            $element.children("div").find('.row-copy').unbind();
            $element.children("div").find('.row-copy').click(function () {
                $activeRow = jQuery(this).parents('.ui-draggable');
                jQuery('#temp-contentbuilder').html($activeRow.html());
                jQuery('#temp-contentbuilder').find('[contenteditable]').removeAttr('contenteditable');
                jQuery('#temp-contentbuilder *[class=""]').removeAttr('class');
                jQuery('#temp-contentbuilder *[style=""]').removeAttr('style');
                jQuery('#temp-contentbuilder .ovl').remove();
                jQuery('#temp-contentbuilder').find('p').each(function () {
                    if (jQuery.trim(jQuery(this).text()) == '') jQuery(this).remove()
                });
                jQuery('#temp-contentbuilder .row-tool').remove();
                var html = jQuery('#temp-contentbuilder').html().trim();
                $activeRow.after(html);
                $element.children("*").each(function () {
                    if (!jQuery(this).hasClass('ui-draggable')) {
                        jQuery(this).wrap("<div class='ui-draggable'></div>")
                    }
                });
                $element.children('.ui-draggable').each(function () {
                    if (jQuery(this).find('.row-tool').length == 0) {
                        jQuery(this).append('<div class="row-tool">' + '<div class="row-handle"><i class="cb-icon-move"></i></div>' + '<div class="row-html"><i class="cb-icon-code"></i></div>' + '<div class="row-copy"><i class="cb-icon-plus"></i></div>' + '<div class="row-remove"><i class="cb-icon-cancel"></i></div>' + '</div>')
                    }
                });
                $element.children('.ui-draggable').each(function () {
                    if (jQuery(this).children('*').length == 1) {
                        jQuery(this).remove()
                    }
                });
                $element.data('contentbuilder').applyBehavior();
                $element.data('contentbuilder').settings.onRender()
            });
            $element.children("div").find('.row-html').unbind();
            $element.children("div").find('.row-html').click(function () {
                jQuery('#md-html').css('width', '45%');
                jQuery('#md-html').simplemodal();
                jQuery('#md-html').data('simplemodal').show();
                $activeRow = jQuery(this).parents('.ui-draggable').children('*').not('.row-tool');
                if ($activeRow.data('mode') == 'code') {
                    jQuery('#txtHtml').val(decodeURIComponent($activeRow.attr('data-html')))
                } else {
                    jQuery('#temp-contentbuilder').html($activeRow.html());
                    jQuery('#temp-contentbuilder').find('[contenteditable]').removeAttr('contenteditable');
                    jQuery('#temp-contentbuilder *[class=""]').removeAttr('class');
                    jQuery('#temp-contentbuilder *[style=""]').removeAttr('style');
                    jQuery('#temp-contentbuilder .ovl').remove();
                    jQuery('#temp-contentbuilder').find('p').each(function () {
                        if (jQuery.trim(jQuery(this).text()) == '') jQuery(this).remove()
                    });
                    var html = jQuery('#temp-contentbuilder').html().trim();
                    html = html.replace(/<font/g, '<span').replace(/<\/font/g, '</span');
                    jQuery('#txtHtml').val(html)
                }
                jQuery('#btnHtmlOk').unbind('click');
                jQuery('#btnHtmlOk').bind('click', function (e) {
                    if ($activeRow.data('mode') == 'code') {
                        $activeRow.attr('data-html', encodeURIComponent(jQuery('#txtHtml').val()));
                        $activeRow.html('')
                    } else {
                        $activeRow.html(jQuery('#txtHtml').val())
                    }
                    jQuery('#md-html').data('simplemodal').hide();
                    $element.data('contentbuilder').applyBehavior();
                    $element.data('contentbuilder').settings.onRender()
                })
            })
        };
        this.destroy = function () {
            if (!$element.data('contentbuilder')) return;
            var sHTML = $element.data('contentbuilder').html();
            $element.html(sHTML);
            $element.removeClass('connectSortable');
            $element.css({
                'min-height': ''
            });
            jQuery('#divCb').remove();
            $element.removeData('contentbuilder');
            $element.removeData('contenteditor');
            $element.unbind();
            jQuery(document).unbind('mousedown')
        };
        this.init()
    };
    jQuery.fn.contentbuilder = function (options) {
        return this.each(function () {
            if (undefined == jQuery(this).data('contentbuilder')) {
                var plugin = new jQuery.contentbuilder(this, options);
                jQuery(this).data('contentbuilder', plugin)
            }
        })
    }
})(jQuery);
(function (jQuery) {
    var $activeLink;
    var $activeElement;
    var $activeFrame;
    var instances = [];

    function instances_count() {};
    jQuery.fn.count = function () {};
    jQuery.contenteditor = function (element, options) {
        var defaults = {
            editable: "h1,h2,h3,h4,h5,h6,p,ul,ol,small,.edit",
            editMode: "default",
            hasChanged: false,
            onRender: function () {},
            outline: false,
            fileselect: '',
            colors: ["#ffffc5", "#e9d4a7", "#ffd5d5", "#ffd4df", "#c5efff", "#b4fdff", "#c6f5c6", "#fcd1fe", "#ececec", "#f7e97a", "#d09f5e", "#ff8d8d", "#ff80aa", "#63d3ff", "#7eeaed", "#94dd95", "#ef97f3", "#d4d4d4", "#fed229", "#cc7f18", "#ff0e0e", "#fa4273", "#00b8ff", "#0edce2", "#35d037", "#d24fd7", "#888888", "#ff9c26", "#955705", "#c31313", "#f51f58", "#1b83df", "#0bbfc5", "#1aa71b", "#ae19b4", "#333333"]
        };
        this.settings = {};
        var $element = jQuery(element),
            element = element;
        this.init = function () {
            this.settings = jQuery.extend({}, defaults, options);
            var bUseCustomFileSelect = false;
            if (this.settings.fileselect != '') bUseCustomFileSelect = true;
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>')
            }
            var html_rte = '<div id="rte-toolbar">' + '<a href="#" data-rte-cmd="bold"> <i class="cb-icon-bold"></i> </a>' + '<a href="#" data-rte-cmd="italic"> <i class="cb-icon-italic"></i> </a>' + '<a href="#" data-rte-cmd="underline"> <i class="cb-icon-underline"></i> </a>' + '<a href="#" data-rte-cmd="strikethrough"> <i class="cb-icon-strike"></i> </a>' + '<a href="#" data-rte-cmd="color"> <i class="cb-icon-color"></i> </a>' +/* '<a href="#" data-rte-cmd="fontsize"> <i class="cb-icon-fontsize"></i> </a>' +*/ '<a href="#" data-rte-cmd="removeFormat"> <i class="cb-icon-eraser"></i> </a>' + '<a href="#" data-rte-cmd="formatPara"> <i class="cb-icon-header"></i> </a>' +/* '<a href="#" data-rte-cmd="font"> <i class="cb-icon-font"></i> </a>' +*/ '<a href="#" data-rte-cmd="align"> <i class="cb-icon-align-justify"></i> </a>' + '<a href="#" data-rte-cmd="list"> <i class="cb-icon-list-bullet" style="font-size:14px;line-height:1.3"></i> </a>' + '<a href="#" data-rte-cmd="createLink"> <i class="cb-icon-link"></i> </a>' + '<a href="#" data-rte-cmd="unlink"> <i class="cb-icon-unlink"></i> </a>' + '<a href="#" data-rte-cmd="removeElement"> <i class="cb-icon-trash"></i> </a>' + '</div>' + '' + '<div id="divRteLink">' + '<i class="cb-icon-link"></i> Edit Link' + '</div>' + '' + '<div id="divFrameLink">' + '<i class="cb-icon-link"></i> Edit Link' + '</div>' + '' + '<div class="md-modal" id="md-createlink">' + '<div class="md-content">' + '<div class="md-body">' + '<div class="md-label">URL:</div>' + (bUseCustomFileSelect ? '<input type="text" id="txtLink" class="inptxt" style="float:left;width:50%;" value="http:/' + '/"></input><i class="cb-icon-link md-btnbrowse" id="btnLinkBrowse" style="width:10%;"></i>' : '<input type="text" id="txtLink" class="inptxt" value="http:/' + '/" style="float:left;width:60%"></input>') + '<br style="clear:both">' + '<div class="md-label">Text:</div>' + '<input type="text" id="txtLinkText" class="inptxt" style="float:right;width:60%"></input>' + '<br style="clear:both">' + '<div class="md-label">Target:</div>' + '<label style="float:left;" for="chkNewWindow" class="inpchk"><input type="checkbox" id="chkNewWindow"></input> New Window</label>' + '</div>' + '<div class="md-footer">' + '<button id="btnLinkOk"> Ok </button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-createsrc">' + '<div class="md-content">' + '<div class="md-body">' + '<input type="text" id="txtSrc" class="inptxt" value="http:/' + '/"></input>' + '</div>' + '<div class="md-footer">' + '<button id="btnSrcOk"> Ok </button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-align" style="background:#fff;padding:15px 0px 15px 15px;border-radius:12px">' + '<div class="md-content">' + '<div class="md-body">' + '<button class="md-pickalign" data-align="left"> <i class="cb-icon-align-left"></i> <span>Left</span> </button>' + '<button class="md-pickalign" data-align="center"> <i class="cb-icon-align-center"></i> <span>Center</span> </button>' + '<button class="md-pickalign" data-align="right"> <i class="cb-icon-align-right"></i> <span>Right</span> </button>' + '<button class="md-pickalign" data-align="justify"> <i class="cb-icon-align-justify"></i> <span>Full</span> </button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-list" style="background:#fff;padding:15px 0px 15px 15px;border-radius:12px">' + '<div class="md-content">' + '<div class="md-body">' + '<button class="md-picklist half" data-list="indent" style="margin-right:0px"> <i class="cb-icon-indent-left"></i> </button>' + '<button class="md-picklist half" data-list="outdent"> <i class="cb-icon-indent-right"></i> </button>' + '<button class="md-picklist" data-list="insertUnorderedList"> <i class="cb-icon-list-bullet"></i> <span>Bullet</span> </button>' + '<button class="md-picklist" data-list="insertOrderedList"> <i class="cb-icon-list-numbered"></i> <span>Numbered</span> </button>' + '<button class="md-picklist" data-list="normal"> <i class="cb-icon-cancel"></i> <span>None</span> </button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-fonts" style="border-radius:12px">' + '<div class="md-content" style="border-radius:12px">' + '<div class="md-body">' + '<iframe id="ifrFonts" style="width:100%;height:371px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' + '<button class="md-pickfontfamily" data-font-family="" data-provider="" style="display:none"></button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-fontsize" style="border-radius:12px">' + '<div class="md-content" style="border-radius:12px">' + '<div class="md-body">' + '<iframe id="ifrFontSize" style="width:100%;height:319px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' + '<button class="md-pickfontsize" data-font-size="" style="display:none"></button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-headings" style="border-radius:12px">' + '<div class="md-content" style="border-radius:12px">' + '<div class="md-body">' + '<iframe id="ifrHeadings" style="width:100%;height:335px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' + '<button class="md-pickheading" data-heading="" style="display:none"></button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-color" style="background:#fff;padding:15px 0px 15px 15px;border-radius:12px">' + '<div class="md-content">' + '<div class="md-body">' + '<div style="width:100%">' + '<select id="selColorApplyTo" style="width:85%"><option value="1">Text Color</option><option value="2">Background</option><option value="3">Block Background</option></select>' + '<button id="btnCleanColor" style="cursor: pointer;background: #FFFFFF;border: none;margin: 0 0 0 10px;vertical-align: middle;"><i class="cb-icon-eraser" style="color:#555;font-size:25px"></i></button>' + '</div>' + '[COLORS]' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-html">' + '<div class="md-content">' + '<div class="md-body">' + '<textarea id="txtHtml" class="inptxt" style="height:350px;"></textarea>' + '</div>' + '<div class="md-footer">' + '<button id="btnHtmlOk"> Ok </button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-fileselect">' + '<div class="md-content">' + '<div class="md-body">' + (bUseCustomFileSelect ? '<iframe id="ifrFileBrowse" style="width:100%;height:400px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' : '') + '</div>' + '</div>' + '</div>' + '<input type="hidden" id="active-input" />' + '' + '<div id="temp-contenteditor"></div>' + '';
            var html_colors = '';
            for (var i = 0; i < this.settings.colors.length; i++) {
                if (this.settings.colors[i] == '#ececec') {
                    html_colors += '<button class="md-pick" style="background:' + this.settings.colors[i] + ';border:#e7e7e7 1px solid"></button>'
                } else {
                    html_colors += '<button class="md-pick" style="background:' + this.settings.colors[i] + ';border:' + this.settings.colors[i] + ' 1px solid"></button>'
                }
            }
            html_rte = html_rte.replace('[COLORS]', html_colors);
            if (jQuery('#rte-toolbar').length == 0) {
                jQuery('#divCb').append(html_rte);
                this.prepareRteCommand('bold');
                this.prepareRteCommand('italic');
                this.prepareRteCommand('underline');
                this.prepareRteCommand('strikethrough');
                this.prepareRteCommand('undo');
                this.prepareRteCommand('redo')
            }
            var isCtrl = false;
            $element.bind('keyup', function (e) {
                $element.data('contenteditor').realtime()
            });
            $element.bind('mouseup', function (e) {
                $element.data('contenteditor').realtime()
            });
            jQuery(document).on("paste", '#' + $element.attr('id'), function (e) {
                pasteContent($activeElement)
            });
            $element.bind('keydown', function (e) {
                if (e.which == 46 || e.which == 8) {
                    var el;
                    try {
                        if (window.getSelection) {
                            el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode
                        } else if (document.selection) {
                            el = document.selection.createRange().parentElement()
                        }
                        if (el.nodeName.toLowerCase() == 'p') {
                            var t = '';
                            if (window.getSelection) {
                                t = window.getSelection().toString()
                            } else if (document.getSelection) {
                                t = document.getSelection().toString()
                            } else if (document.selection) {
                                t = document.selection.createRange().text
                            }
                            if (t == el.innerText) {
                                jQuery(el).html('<br>');
                                return false
                            }
                        }
                    } catch (e) {}
                }
                if (e.which == 17) {
                    isCtrl = true;
                    return
                }
                if ((e.which == 86 && isCtrl == true) || (e.which == 86 && e.metaKey)) {
                    pasteContent($activeElement)
                }
            }).keyup(function (e) {
                if (e.which == 17) {
                    isCtrl = false
                }
            });
            jQuery(document).on('mousedown', function (event) {
                var bEditable = false;
                if (jQuery('#rte-toolbar').css('display') == 'none') return;
                var el = jQuery(event.target).prop("tagName").toLowerCase();
                if ((jQuery(event.target).is('[contenteditable]') || jQuery(event.target).css('position') == 'absolute' || jQuery(event.target).css('position') == 'fixed' || jQuery(event.target).attr('id') == 'rte-toolbar') && el != 'img' && el != 'hr') {
                    bEditable = true;
                    return
                }
                jQuery(event.target).parents().each(function (e) {
                    if (jQuery(this).is('[contenteditable]') || jQuery(this).css('position') == 'absolute' || jQuery(this).css('position') == 'fixed' || jQuery(this).attr('id') == 'rte-toolbar') {
                        bEditable = true;
                        return
                    }
                });
                if (!bEditable) {
                    $activeElement = null;
                    jQuery('#rte-toolbar').css('display', 'none');
                    if ($element.data('contenteditor').settings.outline) {
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).css('outline', '');
                            jQuery(instances[i]).find('*').css('outline', '')
                        }
                    }
                }
            })
        };
        this.realtime = function () {
            var is_ie = detectIE();
            var el;
            try {
                if (window.getSelection) {
                    el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode
                } else if (document.selection) {
                    el = document.selection.createRange().parentElement()
                }
            } catch (e) {
                return
            }
            if (jQuery(el).parents("[data-mode='code']").length > 0) return;
            if (el.nodeName.toLowerCase() == 'a') {
                if (is_ie) {} else {}
                jQuery("#divRteLink").addClass('forceshow')
            } else {
                jQuery("#divRteLink").removeClass('forceshow')
            }
        };
        this.render = function () {
            var zoom;
            if (localStorage.getItem("zoom") != null) {
                zoom = localStorage.zoom
            } else {
                zoom = $element.css('zoom')
            }
            if (zoom == undefined) zoom = 1;
            localStorage.zoom = zoom;
            var editable = $element.data('contenteditor').settings.editable;
            if (editable == '') {
                $element.attr('contenteditable', 'true');
                $element.unbind('mousedown');
                $element.bind('mousedown', function (e) {
                    $activeElement = jQuery(this);
                    jQuery("#rte-toolbar").stop(true, true).fadeIn(200);
                    if ($element.data('contenteditor').settings.outline) {
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).css('outline', '');
                            jQuery(instances[i]).find('*').css('outline', '')
                        }
                        jQuery(this).css('outline', 'rgba(0, 0, 0, 0.43) dashed 1px')
                    }
                })
            } else {
                $element.find(editable).each(function () {
                    if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                    var editMode = $element.data('contenteditor').settings.editMode;
                    if (editMode == 'default') {} else {
                        var attr = jQuery(this).attr('contenteditable');
                        if (typeof attr !== typeof undefined && attr !== false) {} else {
                            jQuery(this).attr('contenteditable', 'true')
                        }
                    }
                });
                $element.find(editable).unbind('mousedown');
                $element.find(editable).bind('mousedown', function (e) {
                    $activeElement = jQuery(this);
                    jQuery("#rte-toolbar").stop(true, true).fadeIn(200);
                    if ($element.data('contenteditor').settings.outline) {
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).css('outline', '');
                            jQuery(instances[i]).find('*').css('outline', '')
                        }
                        jQuery(this).css('outline', 'rgba(0, 0, 0, 0.43) dashed 1px')
                    }
                });
                $element.find('.edit').find(editable).removeAttr('contenteditable')
            }
            $element.find('a').each(function () {
                if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                jQuery(this).attr('contenteditable', 'true')
            });
            var editMode = $element.data('contenteditor').settings.editMode;
            if (editMode == 'default') {
                $element.find("h1,h2,h3,h4,h5,h6").unbind('keydown');
                $element.find("h1,h2,h3,h4,h5,h6").bind('keydown', function (e) {
                    if (e.keyCode == 13) {
                        var is_ie = detectIE();
                        if (is_ie && is_ie <= 10) {
                            var oSel = document.selection.createRange();
                            if (oSel.parentElement) {
                                oSel.pasteHTML('<br>');
                                e.cancelBubble = true;
                                e.returnValue = false;
                                oSel.select();
                                oSel.moveEnd("character", 1);
                                oSel.moveStart("character", 1);
                                oSel.collapse(false);
                                return false
                            }
                        } else {
                            var oSel = window.getSelection();
                            var range = oSel.getRangeAt(0);
                            range.extractContents();
                            range.collapse(true);
                            var docFrag = range.createContextualFragment('<br>');
                            var lastNode = docFrag.lastChild;
                            range.insertNode(docFrag);
                            range.setStartAfter(lastNode);
                            range.setEndAfter(lastNode);
                            if (range.endContainer.nodeType == 1) {
                                if (range.endOffset == range.endContainer.childNodes.length - 1) {
                                    range.insertNode(range.createContextualFragment("<br />"));
                                    range.setStartAfter(lastNode);
                                    range.setEndAfter(lastNode)
                                }
                            }
                            var comCon = range.commonAncestorContainer;
                            if (comCon && comCon.parentNode) {
                                try {
                                    comCon.parentNode.normalize()
                                } catch (e) {}
                            }
                            oSel.removeAllRanges();
                            oSel.addRange(range);
                            return false
                        }
                    }
                });
                $element.find("h1,h2,h3,h4,h5,h6,p").each(function () {
                    jQuery(this).parent().attr('contenteditable', true)
                });
                $element.find("div").unbind('keyup');
                $element.find("div").bind('keyup', function (e) {
                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode
                    } else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement()
                    }
                    if (e.keyCode == 13) {
                        var is_ie = detectIE();
                        if (is_ie > 0) {} else {
                            var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
                            var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
                            var isOpera = window.opera;
                            var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                            if (isChrome || isOpera) {
                                if (jQuery(el).prop("tagName").toLowerCase() == 'p' || jQuery(el).prop("tagName").toLowerCase() == 'div') {
                                    document.execCommand('formatBlock', false, '<p>')
                                }
                            }
                            if (isFirefox) {
                                if (!jQuery(curr).html()) document.execCommand('formatBlock', false, '<p>')
                            }
                        }
                    }
                })
            } else {
                $element.find("p").unbind('keydown');
                $element.find("p").bind('keydown', function (e) {
                    if (e.keyCode == 13 && $element.find("li").length == 0) {
                        var UA = navigator.userAgent.toLowerCase();
                        var LiveEditor_isIE = (UA.indexOf('msie') >= 0) ? true : false;
                        if (LiveEditor_isIE) {
                            var oSel = document.selection.createRange();
                            if (oSel.parentElement) {
                                oSel.pasteHTML('<br>');
                                e.cancelBubble = true;
                                e.returnValue = false;
                                oSel.select();
                                oSel.moveEnd("character", 1);
                                oSel.moveStart("character", 1);
                                oSel.collapse(false);
                                return false
                            }
                        } else {
                            var oSel = window.getSelection();
                            var range = oSel.getRangeAt(0);
                            range.extractContents();
                            range.collapse(true);
                            var docFrag = range.createContextualFragment('<br>');
                            var lastNode = docFrag.lastChild;
                            range.insertNode(docFrag);
                            range.setStartAfter(lastNode);
                            range.setEndAfter(lastNode);
                            if (range.endContainer.nodeType == 1) {
                                if (range.endOffset == range.endContainer.childNodes.length - 1) {
                                    range.insertNode(range.createContextualFragment("<br />"));
                                    range.setStartAfter(lastNode);
                                    range.setEndAfter(lastNode)
                                }
                            }
                            var comCon = range.commonAncestorContainer;
                            if (comCon && comCon.parentNode) {
                                try {
                                    comCon.parentNode.normalize()
                                } catch (e) {}
                            }
                            oSel.removeAllRanges();
                            oSel.addRange(range);
                            return false
                        }
                    }
                })
            }
            jQuery('#rte-toolbar a[data-rte-cmd="removeElement"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="removeElement"]').click(function (e) {
                $activeElement.remove();
                $element.data('contenteditor').settings.hasChanged = true;
                $element.data('contenteditor').render();
                e.preventDefault()
            });
            jQuery('#rte-toolbar a[data-rte-cmd="color"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="color"]').click(function (e) {
                var savedSel = saveSelection();
                jQuery('#md-color').css('max-width', '465px');
                jQuery('#md-color').simplemodal();
                jQuery('#md-color').data('simplemodal').show();
                e.preventDefault();
                var text = getSelected();
                jQuery('.md-pick').unbind('click');
                jQuery('.md-pick').click(function () {
                    restoreSelection(savedSel);
                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode
                    } else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement()
                    }
                    var selColMode = jQuery('#selColorApplyTo').val();
                    if (jQuery.trim(text) != '' && jQuery(curr).text() != text) {
                        if (selColMode == 1) {
                            document.execCommand("ForeColor", false, jQuery(this).css("background-color"))
                        }
                        if (selColMode == 2) {
                            document.execCommand("BackColor", false, jQuery(this).css("background-color"))
                        }
                        var fontElements = document.getElementsByTagName("font");
                        for (var i = 0, len = fontElements.length; i < len; ++i) {
                            var s = fontElements[i].color;
                            fontElements[i].removeAttribute("color");
                            fontElements[i].style.color = s
                        }
                        var is_ie = detectIE();
                        if (is_ie) {
                            $activeElement.find('span').each(function () {
                                if (jQuery(this).find('span').length == 1) {
                                    if (jQuery(this).text() == jQuery(this).find('span:first').text()) {
                                        var innerspanstyle = jQuery(this).find('span:first').attr('style');
                                        jQuery(this).html(jQuery(this).find('span:first').html());
                                        var newstyle = jQuery(this).attr('style') + ';' + innerspanstyle;
                                        jQuery(this).attr('style', newstyle)
                                    }
                                }
                            })
                        }
                    } else if (jQuery(curr).text() == text) {
                        if (selColMode == 1) {
                            if (jQuery(curr).html()) {
                                jQuery(curr).css('color', jQuery(this).css("background-color"))
                            } else {
                                jQuery(curr).parent().css('color', jQuery(this).css("background-color"))
                            }
                        }
                        if (selColMode == 2) {
                            if (jQuery(curr).html()) {
                                jQuery(curr).css('background-color', jQuery(this).css("background-color"))
                            } else {
                                jQuery(curr).parent().css('background-color', jQuery(this).css("background-color"))
                            }
                        }
                    } else {
                        if (selColMode == 1) {
                            jQuery(el).css('color', jQuery(this).css("background-color"))
                        }
                        if (selColMode == 2) {
                            jQuery(el).css('background-color', jQuery(this).css("background-color"))
                        }
                    };
                    if (selColMode == 3) {
                        jQuery(el).parents('.ui-draggable').children('div').css('background-color', jQuery(this).css("background-color"))
                    }
                    jQuery('#md-color').data('simplemodal').hide()
                });
                jQuery('#btnCleanColor').unbind('click');
                jQuery('#btnCleanColor').click(function () {
                    restoreSelection(savedSel);
                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode
                    } else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement()
                    }
                    var selColMode = jQuery('#selColorApplyTo').val();
                    if (jQuery.trim(text) != '' && jQuery(curr).text() != text) {
                        if (selColMode == 1) {
                            document.execCommand("ForeColor", false, '')
                        }
                        if (selColMode == 2) {
                            document.execCommand("BackColor", false, '')
                        }
                        var fontElements = document.getElementsByTagName("font");
                        for (var i = 0, len = fontElements.length; i < len; ++i) {
                            var s = fontElements[i].color;
                            fontElements[i].removeAttribute("color");
                            fontElements[i].style.color = s
                        }
                    } else if (jQuery(curr).text() == text) {
                        if (selColMode == 1) {
                            if (jQuery(curr).html()) {
                                jQuery(curr).css('color', '')
                            } else {
                                jQuery(curr).parent().css('color', '')
                            }
                        }
                        if (selColMode == 2) {
                            if (jQuery(curr).html()) {
                                jQuery(curr).css('background-color', '')
                            } else {
                                jQuery(curr).parent().css('background-color', '')
                            }
                        }
                    } else {
                        if (selColMode == 1) {
                            jQuery(el).css('color', '')
                        }
                        if (selColMode == 2) {
                            jQuery(el).css('background-color', '')
                        }
                    };
                    if (selColMode == 3) {
                        jQuery(curr).parents('.ui-draggable').children('div').css('background-color', '')
                    }
                    jQuery('#md-color').data('simplemodal').hide()
                })
            });
            jQuery('#rte-toolbar a[data-rte-cmd="fontsize"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="fontsize"]').click(function (e) {
                var savedSel = saveSelection();
                jQuery('#md-fontsize').css('max-width', '190px');
                jQuery('#md-fontsize').simplemodal();
                jQuery('#md-fontsize').data('simplemodal').show();
                e.preventDefault();
                if (jQuery('#ifrFontSize').attr('src').indexOf('fontsize.html') == -1) {
                    jQuery('#ifrFontSize').attr('src', sScriptPath + 'fontsize.html')
                }
                var text = getSelected();
                jQuery('.md-pickfontsize').unbind('click');
                jQuery('.md-pickfontsize').click(function () {
                    restoreSelection(savedSel);
                    var el;
                    var curr;
                    if (window.getSelection) {
                        curr = window.getSelection().getRangeAt(0).commonAncestorContainer;
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode
                    } else if (document.selection) {
                        curr = document.selection.createRange();
                        el = document.selection.createRange().parentElement()
                    }
                    var s = jQuery(this).attr('data-font-size');
                    if (jQuery.trim(text) != '' && jQuery(curr).text() != text) {
                        document.execCommand("fontSize", false, "7");
                        var fontElements = document.getElementsByTagName("font");
                        for (var i = 0, len = fontElements.length; i < len; ++i) {
                            if (fontElements[i].size == "7") {
                                fontElements[i].removeAttribute("size");
                                fontElements[i].style.fontSize = s
                            }
                        }
                    } else if (jQuery(curr).text() == text) {
                        if (jQuery(curr).html()) {
                            jQuery(curr).css('font-size', s)
                        } else {
                            jQuery(curr).parent().css('font-size', s)
                        }
                    } else {
                        jQuery(el).css('font-size', s)
                    };
                    jQuery(this).blur();
                    $element.data('contenteditor').settings.hasChanged = true;
                    e.preventDefault();
                    jQuery('#md-fontsize').data('simplemodal').hide()
                })
            });
            jQuery('#rte-toolbar a[data-rte-cmd="formatPara"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="formatPara"]').click(function (e) {
                var savedSel = saveSelection();
                jQuery('#md-headings').css('max-width', '225px');
                jQuery('#md-headings').simplemodal();
                jQuery('#md-headings').data('simplemodal').show();
                e.preventDefault();
                if (jQuery('#ifrHeadings').attr('src').indexOf('headings.html') == -1) {
                    jQuery('#ifrHeadings').attr('src', sScriptPath + 'headings.html')
                }
                jQuery('.md-pickheading').unbind('click');
                jQuery('.md-pickheading').click(function () {
                    restoreSelection(savedSel);
                    var s = jQuery(this).attr('data-heading');
                    $element.attr('contenteditable', true);
                    document.execCommand('formatBlock', false, '<' + s + '>');
                    $element.removeAttr('contenteditable');
                    $element.data('contenteditor').render();
                    jQuery(this).blur();
                    $element.data('contenteditor').settings.hasChanged = true;
                    e.preventDefault();
                    jQuery('#md-headings').data('simplemodal').hide()
                })
            });
            jQuery('#rte-toolbar a[data-rte-cmd="removeFormat"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="removeFormat"]').click(function (e) {
                document.execCommand('removeFormat', false, null);
                document.execCommand('removeFormat', false, null);
                jQuery(this).blur();
                $element.data('contenteditor').settings.hasChanged = true;
                e.preventDefault()
            });
            jQuery('#rte-toolbar a[data-rte-cmd="unlink"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="unlink"]').click(function (e) {
                document.execCommand('unlink', false, null);
                jQuery("#divRteLink").removeClass('forceshow');
                jQuery(this).blur();
                $element.data('contenteditor').settings.hasChanged = true;
                e.preventDefault()
            });
            jQuery('#rte-toolbar a[data-rte-cmd="font"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="font"]').click(function (e) {
                var savedSel = saveSelection();
                jQuery('#md-fonts').css('max-width', '300px');
                jQuery('#md-fonts').simplemodal();
                jQuery('#md-fonts').data('simplemodal').show();
                e.preventDefault();
                if (jQuery('#ifrFonts').attr('src').indexOf('fonts.html') == -1) {
                    jQuery('#ifrFonts').attr('src', sScriptPath + 'fonts.html')
                }
                jQuery('.md-pickfontfamily').unbind('click');
                jQuery('.md-pickfontfamily').click(function () {
                    restoreSelection(savedSel);
                    var el;
                    if (window.getSelection) {
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' && el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' && el.nodeName != 'P') {
                            el = el.parentNode
                        }
                    } else if (document.selection) {
                        el = document.selection.createRange().parentElement();
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' && el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' && el.nodeName != 'P') {
                            el = el.parentElement()
                        }
                    }
                    var s = jQuery(this).attr('data-font-family');
                    jQuery(el).css('font-family', s);
                    var fontname = s.split(',')[0];
                    var provider = jQuery(this).attr('data-provider');
                    if (provider == 'google') {
                        var bExist = false;
                        var links = document.getElementsByTagName("link");
                        for (var i = 0; i < links.length; i++) {
                            var sSrc = links[i].href.toLowerCase();
                            sSrc = sSrc.replace(/\+/g, ' ').replace(/%20/g, ' ');
                            if (sSrc.indexOf(fontname.toLowerCase()) != -1) bExist = true
                        }
                        if (!bExist) $element.append('<link href="http://fonts.googleapis.com/css?family=' + fontname + '" rel="stylesheet" property="stylesheet" type="text/css">')
                    }
                    $element.find('link').each(function () {
                        var sSrc = jQuery(this).attr('href').toLowerCase();
                        if (sSrc.indexOf('googleapis') != -1) {
                            sSrc = sSrc.replace(/\+/g, ' ').replace(/%20/g, ' ');
                            var fontname = sSrc.substr(sSrc.indexOf('family=') + 7);
                            if (fontname.indexOf(':') != -1) {
                                fontname = fontname.split(':')[0]
                            }
                            if (fontname.indexOf('|') != -1) {
                                fontname = fontname.split('|')[0]
                            }
                            var tmp = $element.html().toLowerCase();
                            var count = tmp.split(fontname).length;
                            if (count < 3) {
                                jQuery(this).attr('rel', '_del')
                            }
                        }
                    });
                    $element.find('[rel="_del"]').remove();
                    jQuery(this).blur();
                    $element.data('contenteditor').settings.hasChanged = true;
                    e.preventDefault();
                    jQuery('#md-fonts').data('simplemodal').hide()
                })
            });
            jQuery('#rte-toolbar a[data-rte-cmd="align"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="align"]').click(function (e) {
                var savedSel = saveSelection();
                jQuery('#md-align').css('max-width', '185px');
                jQuery('#md-align').simplemodal();
                jQuery('#md-align').data('simplemodal').show();
                e.preventDefault();
                jQuery('.md-pickalign').unbind('click');
                jQuery('.md-pickalign').click(function () {
                    restoreSelection(savedSel);
                    var el;
                    if (window.getSelection) {
                        el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' && el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' && el.nodeName != 'P') {
                            el = el.parentNode
                        }
                    } else if (document.selection) {
                        el = document.selection.createRange().parentElement();
                        if (el.nodeName != 'H1' && el.nodeName != 'H2' && el.nodeName != 'H3' && el.nodeName != 'H4' && el.nodeName != 'H5' && el.nodeName != 'H6' && el.nodeName != 'P') {
                            el = el.parentElement()
                        }
                    }
                    var s = jQuery(this).data('align');
                    el.style.textAlign = s;
                    jQuery(this).blur();
                    $element.data('contenteditor').settings.hasChanged = true;
                    e.preventDefault();
                    jQuery('#md-align').data('simplemodal').hide()
                })
            });
            jQuery('#rte-toolbar a[data-rte-cmd="list"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="list"]').click(function (e) {
                var savedSel = saveSelection();
                jQuery('#md-list').css('max-width', '185px');
                jQuery('#md-list').simplemodal();
                jQuery('#md-list').data('simplemodal').show();
                e.preventDefault();
                jQuery('.md-picklist').unbind('click');
                jQuery('.md-picklist').click(function () {
                    restoreSelection(savedSel);
                    var s = jQuery(this).data('list');
                    try {
                        if (s == 'normal') {
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null)
                        } else {
                            document.execCommand(s, false, null)
                        }
                    } catch (e) {
                        $activeElement.parents('div').addClass('edit');
                        var el;
                        if (window.getSelection) {
                            el = window.getSelection().getRangeAt(0).commonAncestorContainer.parentNode;
                            el = el.parentNode
                        } else if (document.selection) {
                            el = document.selection.createRange().parentElement();
                            el = el.parentElement()
                        }
                        el.setAttribute('contenteditable', true);
                        if (s == 'normal') {
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null);
                            document.execCommand('outdent', false, null)
                        } else {
                            document.execCommand(s, false, null)
                        }
                        el.removeAttribute('contenteditable');
                        $element.data('contenteditor').render()
                    }
                    jQuery(this).blur();
                    $element.data('contenteditor').settings.hasChanged = true;
                    e.preventDefault();
                    jQuery('#md-list').data('simplemodal').hide()
                })
            });
            jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="createLink"]').click(function (e) {
                var html = "";
                if (typeof window.getSelection != "undefined") {
                    var sel = window.getSelection();
                    if (sel.rangeCount) {
                        var container = document.createElement("div");
                        for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                            container.appendChild(sel.getRangeAt(i).cloneContents())
                        }
                        html = container.innerHTML
                    }
                } else if (typeof document.selection != "undefined") {
                    if (document.selection.type == "Text") {
                        html = document.selection.createRange().htmlText
                    }
                }
                if (html == '') {
                    alert('Please select a text.');
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return
                }
                var el;
                if (window.getSelection) {
                    el = window.getSelection().getRangeAt(0).commonAncestorContainer
                } else if (document.selection) {
                    el = document.selection.createRange()
                }
                if (el.nodeName.toLowerCase() == 'a') {
                    $activeLink = jQuery(el)
                } else {
                    document.execCommand('createLink', false, 'http://dummy');
                    $activeLink = jQuery("a[href='http://dummy']").first();
                    $activeLink.attr('href', 'http://')
                }
                jQuery('#md-createlink').css('max-width', '550px');
                jQuery('#md-createlink').simplemodal({
                    onCancel: function () {
                        if ($activeLink.attr('href') == 'http://') $activeLink.replaceWith($activeLink.html())
                    }
                });
                jQuery('#md-createlink').data('simplemodal').show();
                jQuery('#txtLink').val($activeLink.attr('href'));
                jQuery('#txtLinkText').val($activeLink.html());
                if ($activeLink.attr('target') == '_blank') {
                    jQuery('#chkNewWindow').prop('checked', true)
                } else {
                    jQuery('#chkNewWindow').removeAttr('checked')
                }
                jQuery('#btnLinkOk').unbind('click');
                jQuery('#btnLinkOk').bind('click', function (e) {
                    $activeLink.attr('href', jQuery('#txtLink').val());
                    if (jQuery('#txtLink').val() == 'http://' || jQuery('#txtLink').val() == '') {
                        $activeLink.replaceWith($activeLink.html())
                    }
                    $activeLink.html(jQuery('#txtLinkText').val());
                    if (jQuery('#chkNewWindow').is(":checked")) {
                        $activeLink.attr('target', '_blank')
                    } else {
                        $activeLink.removeAttr('target')
                    }
                    jQuery('#md-createlink').data('simplemodal').hide();
                    for (var i = 0; i < instances.length; i++) {
                        jQuery(instances[i]).data('contenteditor').settings.hasChanged = true;
                        jQuery(instances[i]).data('contenteditor').render()
                    }
                });
                e.preventDefault()
            });
            $element.find(".embed-responsive").unbind('hover');
            $element.find(".embed-responsive").hover(function (e) {
                if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                var zoom = localStorage.zoom;
                if (zoom == 'normal') zoom = 1;
                if (zoom == undefined) zoom = 1;
                zoom = zoom + '';
                if (zoom.indexOf('%') != -1) {
                    zoom = zoom.replace('%', '') / 100
                }
                if (zoom == 'NaN') {
                    zoom = 1
                }
                zoom = zoom * 1;
                var _top;
                var _left;
                var scrolltop = jQuery(window).scrollTop();
                var offsettop = jQuery(this).offset().top;
                var offsetleft = jQuery(this).offset().left;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                var is_ie = detectIE();
                var browserok = true;
                if (is_firefox || is_ie) browserok = false;
                if (browserok) {
                    _top = ((offsettop - 20) * zoom) + (scrolltop - scrolltop * zoom);
                    _left = offsetleft * zoom
                } else {
                    if (is_ie) {
                        var space = $element.getPos().top;
                        var adjy_val = (-space / 1.1) * zoom + space / 1.1;
                        var space2 = $element.getPos().left;
                        var adjx_val = -space2 * zoom + space2;
                        var p = jQuery(this).getPos();
                        _top = ((p.top - 20) * zoom) + adjy_val;
                        _left = (p.left * zoom) + adjx_val
                    }
                    if (is_firefox) {
                        _top = offsettop - 20;
                        _left = offsetleft
                    }
                }
                jQuery("#divFrameLink").css("top", _top + "px");
                jQuery("#divFrameLink").css("left", _left + "px");
                jQuery("#divFrameLink").stop(true, true).css({
                    display: 'none'
                }).fadeIn(20);
                $activeFrame = jQuery(this).find('iframe');
                jQuery("#divFrameLink").unbind('click');
                jQuery("#divFrameLink").bind('click', function (e) {
                    jQuery('#md-createsrc').css('max-width', '550px');
                    jQuery('#md-createsrc').simplemodal();
                    jQuery('#md-createsrc').data('simplemodal').show();
                    jQuery('#txtSrc').val($activeFrame.attr('src'));
                    jQuery('#btnSrcOk').unbind('click');
                    jQuery('#btnSrcOk').bind('click', function (e) {
                        var srcUrl = jQuery('#txtSrc').val();
                        var youRegex = /^http[s]?:\/\/(((www.youtube.com\/watch\?(feature=player_detailpage&)?)v=)|(youtu.be\/))([^#\&\?]*)/;
                        var vimeoRegex = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/)|(video\/))?([0-9]+)\/?/;
                        var youRegexMatches = youRegex.exec(srcUrl);
                        var vimeoRegexMatches = vimeoRegex.exec(srcUrl);
                        if (youRegexMatches != null || vimeoRegexMatches != null) {
                            if (youRegexMatches != null && youRegexMatches.length >= 7) {
                                var youMatch = youRegexMatches[6];
                                srcUrl = '//www.youtube.com/embed/' + youMatch + '?rel=0'
                            }
                            if (vimeoRegexMatches != null && vimeoRegexMatches.length >= 7) {
                                var vimeoMatch = vimeoRegexMatches[6];
                                srcUrl = '//player.vimeo.com/video/' + vimeoMatch
                            }
                        }
                        $activeFrame.attr('src', srcUrl);
                        if (jQuery('#txtSrc').val() == '') {
                            $activeFrame.attr('src', '')
                        }
                        jQuery('#md-createsrc').data('simplemodal').hide();
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).data('contenteditor').settings.hasChanged = true;
                            jQuery(instances[i]).data('contenteditor').render()
                        }
                    })
                });
                jQuery("#divFrameLink").hover(function (e) {
                    jQuery(this).stop(true, true).css("display", "block")
                }, function () {
                    jQuery(this).stop(true, true).fadeOut(0)
                })
            }, function (e) {
                jQuery("#divFrameLink").stop(true, true).fadeOut(0)
            });
            $element.find('a').not('.not-a').unbind('hover');
            $element.find('a').not('.not-a').hover(function (e) {
                if (jQuery(this).parents("[data-mode='code']").length > 0) return;
                if (jQuery(this).children('img').length == 1 && jQuery(this).children().length == 1) return;
                var zoom = localStorage.zoom;
                if (zoom == 'normal') zoom = 1;
                if (zoom == undefined) zoom = 1;
                zoom = zoom + '';
                if (zoom.indexOf('%') != -1) {
                    zoom = zoom.replace('%', '') / 100
                }
                if (zoom == 'NaN') {
                    zoom = 1
                }
                zoom = zoom * 1;
                var _top;
                var _left;
                var scrolltop = jQuery(window).scrollTop();
                var offsettop = jQuery(this).offset().top;
                var offsetleft = jQuery(this).offset().left;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                var is_ie = detectIE();
                var browserok = true;
                if (is_firefox || is_ie) browserok = false;
                if (browserok) {
                    _top = ((offsettop - 23) * zoom) + (scrolltop - scrolltop * zoom);
                    _left = offsetleft * zoom
                } else {
                    if (is_ie) {
                        var space = $element.getPos().top;
                        var adjy_val = (-space / 1.1) * zoom + space / 1.1;
                        var space2 = $element.getPos().left;
                        var adjx_val = -space2 * zoom + space2;
                        var p = jQuery(this).getPos();
                        _top = ((p.top - 23) * zoom) + adjy_val;
                        _left = (p.left * zoom) + adjx_val
                    }
                    if (is_firefox) {
                        _top = offsettop - 23;
                        _left = offsetleft
                    }
                }
                jQuery("#divRteLink").css("top", _top + "px");
                jQuery("#divRteLink").css("left", _left + "px");
                jQuery("#divRteLink").stop(true, true).css({
                    display: 'none'
                }).fadeIn(20);
                $activeLink = jQuery(this);
                jQuery("#divRteLink").unbind('click');
                jQuery("#divRteLink").bind('click', function (e) {
                    jQuery('#md-createlink').css('max-width', '550px');
                    jQuery('#md-createlink').simplemodal({
                        onCancel: function () {
                            if ($activeLink.attr('href') == 'http://') $activeLink.replaceWith($activeLink.html())
                        }
                    });
                    jQuery('#md-createlink').data('simplemodal').show();
                    jQuery('#txtLink').val($activeLink.attr('href'));
                    jQuery('#txtLinkText').val($activeLink.html());
                    if ($activeLink.attr('target') == '_blank') {
                        jQuery('#chkNewWindow').prop('checked', true)
                    } else {
                        jQuery('#chkNewWindow').removeAttr('checked')
                    }
                    jQuery('#btnLinkOk').unbind('click');
                    jQuery('#btnLinkOk').bind('click', function (e) {
                        $activeLink.attr('href', jQuery('#txtLink').val());
                        if (jQuery('#txtLink').val() == 'http://' || jQuery('#txtLink').val() == '') {
                            $activeLink.replaceWith($activeLink.html())
                        }
                        $activeLink.html(jQuery('#txtLinkText').val());
                        if (jQuery('#chkNewWindow').is(":checked")) {
                            $activeLink.attr('target', '_blank')
                        } else {
                            $activeLink.removeAttr('target')
                        }
                        jQuery('#md-createlink').data('simplemodal').hide();
                        for (var i = 0; i < instances.length; i++) {
                            jQuery(instances[i]).data('contenteditor').settings.hasChanged = true;
                            jQuery(instances[i]).data('contenteditor').render()
                        }
                    })
                });
                jQuery("#divRteLink").hover(function (e) {
                    jQuery(this).stop(true, true).css("display", "block")
                }, function () {
                    jQuery(this).stop(true, true).fadeOut(0)
                })
            }, function (e) {
                jQuery("#divRteLink").stop(true, true).fadeOut(0)
            });
            jQuery("#btnLinkBrowse").unbind('click');
            jQuery("#btnLinkBrowse").bind('click', function (e) {
                jQuery('#ifrFileBrowse').attr('src', $element.data('contenteditor').settings.fileselect);
                jQuery("#divToolImg").stop(true, true).fadeOut(0);
                jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                jQuery("#divRteLink").stop(true, true).fadeOut(0);
                jQuery("#divFrameLink").stop(true, true).fadeOut(0);
                jQuery('#active-input').val('txtLink');
                jQuery('#md-fileselect').css('width', '65%');
                jQuery('#md-fileselect').simplemodal();
                jQuery('#md-fileselect').data('simplemodal').show()
            });
            $element.data('contenteditor').settings.onRender()
        };
        this.prepareRteCommand = function (s) {
            jQuery('#rte-toolbar a[data-rte-cmd="' + s + '"]').unbind('click');
            jQuery('#rte-toolbar a[data-rte-cmd="' + s + '"]').click(function (e) {
                try {
                    document.execCommand(s, false, null)
                } catch (e) {
                    $element.attr('contenteditable', true);
                    document.execCommand(s, false, null);
                    $element.removeAttr('contenteditable');
                    $element.data('contenteditor').render()
                }
                jQuery(this).blur();
                $element.data('contenteditor').settings.hasChanged = true;
                e.preventDefault()
            })
        };
        this.init()
    };
    jQuery.fn.contenteditor = function (options) {
        return this.each(function () {
            instances.push(this);
            if (undefined == jQuery(this).data('contenteditor')) {
                var plugin = new jQuery.contenteditor(this, options);
                jQuery(this).data('contenteditor', plugin)
            }
        })
    }
})(jQuery);

function pasteContent($activeElement) {
    var savedSel = saveSelection();
    jQuery('#idContentWord').remove();
    var tmptop = $activeElement.offset().top;
    jQuery('#divCb').append("<div style='position:absolute;z-index:-1000;top:" + tmptop + "px;left:-1000px;width:1px;height:1px;overflow:auto;' name='idContentWord' id='idContentWord' contenteditable='true'></div>");
    var pasteFrame = document.getElementById("idContentWord");
    pasteFrame.focus();
    setTimeout(function () {
        try {
            restoreSelection(savedSel);
            var $node = jQuery(getSelectionStartNode());
            if (jQuery('#idContentWord').length == 0) return;
            var sPastedText = '';
            var bRichPaste = false;
            if (jQuery('#idContentWord table').length > 0 || jQuery('#idContentWord img').length > 0 || jQuery('#idContentWord p').length > 0 || jQuery('#idContentWord a').length > 0) {
                bRichPaste = true
            }
            if (bRichPaste) {
                sPastedText = jQuery('#idContentWord').html();
                sPastedText = cleanHTML(sPastedText);
                jQuery('#idContentWord').html(sPastedText);
                if (jQuery('#idContentWord').children('p,h1,h2,h3,h4,h5,h6,ul,li').length > 1) {
                    jQuery('#idContentWord').contents().filter(function () {
                        return (this.nodeType == 3 && jQuery.trim(this.nodeValue) != '')
                    }).wrap("<p></p>").end().filter("br").remove()
                }
                sPastedText = '<div class="edit">' + jQuery('#idContentWord').html() + '</div>'
            } else {
                jQuery('#idContentWord').find('p,h1,h2,h3,h4,h5,h6').each(function () {
                    jQuery(this).html(jQuery(this).html() + ' ')
                });
                sPastedText = jQuery('#idContentWord').text()
            }
            jQuery('#idContentWord').remove();
            var oSel = window.getSelection();
            var range = oSel.getRangeAt(0);
            range.extractContents();
            range.collapse(true);
            var docFrag = range.createContextualFragment(sPastedText);
            var lastNode = docFrag.lastChild;
            range.insertNode(docFrag);
            range.setStartAfter(lastNode);
            range.setEndAfter(lastNode);
            range.collapse(false);
            var comCon = range.commonAncestorContainer;
            if (comCon && comCon.parentNode) {
                try {
                    comCon.parentNode.normalize()
                } catch (e) {}
            }
            oSel.removeAllRanges();
            oSel.addRange(range)
        } catch (e) {
            jQuery('#idContentWord').remove()
        }
    }, 200)
}
var savedSel;

function saveSelection() {
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            var ranges = [];
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                ranges.push(sel.getRangeAt(i))
            }
            return ranges
        }
    } else if (document.selection && document.selection.createRange) {
        return document.selection.createRange()
    }
    return null
};

function restoreSelection(savedSel) {
    if (savedSel) {
        if (window.getSelection) {
            sel = window.getSelection();
            sel.removeAllRanges();
            for (var i = 0, len = savedSel.length; i < len; ++i) {
                sel.addRange(savedSel[i])
            }
        } else if (document.selection && savedSel.select) {
            savedSel.select()
        }
    }
};

function getSelectionStartNode() {
    var node, selection;
    if (window.getSelection) {
        selection = getSelection();
        node = selection.anchorNode
    }
    if (!node && document.selection) {
        selection = document.selection;
        var range = selection.getRangeAt ? selection.getRangeAt(0) : selection.createRange();
        node = range.commonAncestorContainer ? range.commonAncestorContainer : range.parentElement ? range.parentElement() : range.item(0)
    }
    if (node) {
        return (node.nodeName == "#text" ? node.parentNode : node)
    }
};
var getSelectedNode = function () {
        var node, selection;
        if (window.getSelection) {
            selection = getSelection();
            node = selection.anchorNode
        }
        if (!node && document.selection) {
            selection = document.selection;
            var range = selection.getRangeAt ? selection.getRangeAt(0) : selection.createRange();
            node = range.commonAncestorContainer ? range.commonAncestorContainer : range.parentElement ? range.parentElement() : range.item(0)
        }
        if (node) {
            return (node.nodeName == "#text" ? node.parentNode : node)
        }
    };

function getSelected() {
    if (window.getSelection) {
        return window.getSelection()
    } else if (document.getSelection) {
        return document.getSelection()
    } else {
        var selection = document.selection && document.selection.createRange();
        if (selection.text) {
            return selection.text
        }
        return false
    }
    return false
};
(function (jQuery) {
    var tmpCanvas;
    var nInitialWidth;
    var nInitialHeight;
    var $imgActive;
    jQuery.imageembed = function (element, options) {
        var defaults = {
            hiquality: true,
            imageselect: '',
            fileselect: '',
            imageEmbed: true
        };
        this.settings = {};
        var $element = jQuery(element),
            element = element;
        this.init = function () {
            this.settings = jQuery.extend({}, defaults, options);
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>')
            }
            var html_photo_file = '';
            var html_photo_file2 = '';
            if (this.settings.imageEmbed) {
                if (navigator.appName.indexOf('Microsoft') != -1) {
                    html_photo_file = '<div id="divToolImg"><div class="fileinputs"><input type="file" name="file" class="my-file" /><div class="fakefile"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAC+klEQVRoQ+2au24aQRSGz+ySkEvPA9AQubNEhXgCSogEShmZGkSQpTS8AjUNSAjXlCRNStpQ8QK8AI6UOLazM5lZvGRvswsz43hYz0iWZe3uzPnOf25rQOVymcAzWsgAZ1xto3DGBQajsFE4Yx4wIZ0xQSM4RmGjcMY8YEI6Y4LKFy0H/9TCJ7b1VsiOo0PaAAv5Wf4ho/CBPjQhneYokRyezWZQKpW4WzuOA71eD5bLZdrx++vahnSz2YRutwu5XC4RZrPZQL1eP33g4XAI1Wo1FeRYlbVQ+FA1U+kfblitVtBut2Nvf3LgQqEAk8kE2G9VC2MM4/EYRqNRZMsnBy4WizCdTiGfz6vidffhqaw98Ha7hU6nA+v1OuCQfr8PLBV46ySB/bAeoL8qJ0GfHLA/D8P9OOmap/jJAXvq1mq12NB1lW404LL/GVqtD5QTPfwwZEJz+DtcXHwEDPf0z3+f+2mbw17oxvZjhIBgGz71LqFSqcQ6xK8wgT+AyZ0L/t+AMflNz3MiNYZXpXkKI2SDhfKw3V67xYwXAdGQJhT6lj77SqgbHP3ywMLMITeB8GIn84C9PJ3P5/s+vYPdGbxYLGAwGABv3k4aPkSIBYAZMg0tfBs4L6kP+yvy7OoKzt6dg3+UTJrQtABmpOHQThs8PGjbeuMrSuDmbdLLhTbAYZXTgJmTEMrBj+sbbs6yPb1KzMIewOJOWiLh7Nog85UH/7vxobO0bb12QYJrV4jCxZA56OuXb26Oq1pSwOGwTgtPz2gLvaRqv9gzOORXpAiyiywN3jdagXtlwaWACbnf9UWBxdRjbWmnLA1l3qK92kYs79UsOeCYaq3GrOAuokNGnC1SwLRWg4NpT37kpREwHUIwzb9HXs8LWKccZsKK/Nv24IBwYdkIGm5jB+8QuVEyh+WA2XDBqjVygfyvheJAaU9KA6cdoNt1A6ybIqrtMQqr9qhu+xmFdVNEtT1GYdUe1W0/o7Buiqi2xyis2qO67WcU1k0R1fb8BZv85KDCNGIQAAAAAElFTkSuQmCC" /></div></div></div>';
                    html_photo_file2 = ''
                } else {
                    html_photo_file = '<div style="display:none"><input type="file" name="file" class="my-file"></div>';
                    html_photo_file2 = '<div id="divToolImg">' + '<i id="lnkEditImage" class="cb-icon-camera"></i>' + '</div>'
                }
            }
            var html_photo_tool = '<div id="divTempContent" style="display:none"></div>' + '<div class="overlay-bg" style="position:fixed;top:0;left:0;width:1;height:1;z-index:10000;zoom 1;background:#fff;opacity:0.8"></div>' + '<div id="divImageEdit" style="position:absolute;display:none;z-index:10000">' + '<div id="my-mask" style="width:200px;height:200px;overflow:hidden;">' + '<img id="my-image" src="" style="max-width:none" />' + '</div>' + '<div id="img-control" style="margin-top:1px;position:absolute;top:5px;left:7px;opacity:0.8">' + '<input id="btnImageCancel" type="button" value="Cancel" /> ' + '<input id="btnZoomOut" type="button" value="-" /> ' + '<input id="btnZoomIn" type="button" value="+" /> ' + '<input id="btnChangeImage" type="button" value="Ok" />' + '</div>' + '</div>' + '<div style="display:none">' + '<canvas id="myCanvas"></canvas>' + '<canvas id="myTmpCanvas"></canvas>' + '</div>' + '<form id="canvasform" method="post" action="" target="canvasframe" enctype="multipart/form-data">' + html_photo_file + '<input id="hidImage" name="hidImage" type="hidden" />' + '<input id="hidPath" name="hidPath" type="hidden" />' + '<input id="hidFile" name="hidFile" type="hidden" />' + '<input id="hidRefId" name="hidRefId" type="hidden" />' + '<input id="hidImgType" name="hidImgType" type="hidden" />' + '</form>' + '<iframe id="canvasframe" name="canvasframe" style="width:1px;height:1px;border:none;visibility:hidden;position:absolute"></iframe>';
            var bUseCustomImageSelect = false;
            if (this.settings.imageselect != '') bUseCustomImageSelect = true;
            var bUseCustomFileSelect = false;
            if (this.settings.fileselect != '') bUseCustomFileSelect = true;
            var html_hover_icons = html_photo_file2 + '<div id="divToolImgSettings">' + '<i id="lnkImageSettings" class="cb-icon-link"></i>' + '</div>' + '<div id="divToolImgLoader">' + '<i id="lnkImageLoader" class="cb-icon-spin animate-spin"></i>' + '</div>' + '' + '<div class="md-modal" id="md-img">' + '<div class="md-content">' + '<div class="md-body">' + '<div class="md-label">Image URL:</div>' + (bUseCustomImageSelect ? '<input type="text" id="txtImgUrl" class="inptxt" style="float:left;width:50%"></input><i class="cb-icon-link md-btnbrowse" id="btnImageBrowse" style="width:10%;"></i>' : '<input type="text" id="txtImgUrl" class="inptxt" style="float:left;width:60%"></input>') + '<br style="clear:both">' + '<div class="md-label">Alternate Text:</div>' + '<input type="text" id="txtAltText" class="inptxt" style="float:right;width:60%"></input>' + '<br style="clear:both">' + '<div class="md-label">Navigate URL:</div>' + (bUseCustomFileSelect ? '<input type="text" id="txtLinkUrl" class="inptxt" style="float:left;width:50%"></input><i class="cb-icon-link md-btnbrowse" id="btnFileBrowse" style="width:10%;"></i>' : '<input type="text" id="txtLinkUrl" class="inptxt" style="float:left;width:60%"></input>') + '</div>' + '<div class="md-footer">' + '<button id="btnImgOk"> Ok </button>' + '</div>' + '</div>' + '</div>' + '' + '<div class="md-modal" id="md-imageselect">' + '<div class="md-content">' + '<div class="md-body">' + (bUseCustomImageSelect ? '<iframe id="ifrImageBrowse" style="width:100%;height:400px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' : '') + '</div>' + '</div>' + '</div>' + '';
            if (jQuery('#md-fileselect').length == 0) {
                html_hover_icons += '<div class="md-modal" id="md-fileselect">' + '<div class="md-content">' + '<div class="md-body">' + (bUseCustomFileSelect ? '<iframe id="ifrFileBrowse" style="width:100%;height:400px;border: none;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAFElEQVQYV2P8DwQMBADjqCKiggAAmZsj5vuXmnUAAAAASUVORK5CYII="></iframe>' : '') + '</div>' + '</div>' + '</div>'
            }
            if (jQuery('#active-input').length == 0) {
                html_hover_icons += '<input type="hidden" id="active-input" />'
            }
            if (jQuery('#divToolImg').length == 0) {
                if (this.settings.imageEmbed) {
                    jQuery('#divCb').append(html_photo_tool)
                }
                jQuery('#divCb').append(html_hover_icons)
            }
            tmpCanvas = document.getElementById('myTmpCanvas');
            jQuery('.my-file[type=file]').change(function (e) {
                changeImage(e);
                jQuery('#my-image').attr('src', '');
                if (!$imgActive.parent().attr('data-gal')) {
                    jQuery(this).clearInputs()
                }
            });
            $element.hover(function (e) {
                var zoom;
                if (localStorage.getItem("zoom") != null) {
                    zoom = localStorage.zoom
                } else {
                    zoom = $element.parents('[style*="zoom"]').css('zoom');
                    if (zoom == 'normal') zoom = 1;
                    if (zoom == undefined) zoom = 1
                }
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                zoom = zoom + '';
                if (zoom.indexOf('%') != -1) {
                    zoom = zoom.replace('%', '') / 100
                }
                if (zoom == 'NaN') {
                    zoom = 1
                }
                localStorage.zoom = zoom;
                zoom = zoom * 1;
                if (cb_list == '') zoom = 1;
                var _top;
                var _top2;
                var _left;
                var scrolltop = jQuery(window).scrollTop();
                var offsettop = jQuery(this).offset().top;
                var offsetleft = jQuery(this).offset().left;
                var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                var is_ie = detectIE();
                var browserok = true;
                if (is_firefox || is_ie) browserok = false;
                var _top_adj = !jQuery(this).data("imageembed").settings.imageEmbed ? 9 : -35;
                if (browserok) {
                    _top = ((offsettop + parseInt(jQuery(this).css('height')) / 2) - 15) * zoom + (scrolltop - scrolltop * zoom);
                    _left = ((offsetleft + parseInt(jQuery(this).css('width')) / 2) - 15) * zoom;
                    _top2 = _top + _top_adj
                } else {
                    if (is_ie) {
                        var space = 0;
                        var space2 = 0;
                        $element.parents().each(function () {
                            if (jQuery(this).data('contentbuilder')) {
                                space = jQuery(this).getPos().top;
                                space2 = jQuery(this).getPos().left
                            }
                        });
                        var adjy_val = -space * zoom + space;
                        var adjx_val = -space2 * zoom + space2;
                        var p = jQuery(this).getPos();
                        _top = ((p.top - 15 + parseInt(jQuery(this).css('height')) / 2)) * zoom + adjy_val;
                        _left = ((p.left - 15 + parseInt(jQuery(this).css('width')) / 2)) * zoom + adjx_val;
                        _top2 = _top + _top_adj
                    }
                    if (is_firefox) {
                        var imgwidth = parseInt(jQuery(this).css('width'));
                        var imgheight = parseInt(jQuery(this).css('height'));
                        _top = offsettop - 15 + imgheight * zoom / 2;
                        _left = offsetleft - 15 + imgwidth * zoom / 2;
                        _top2 = _top + _top_adj
                    }
                }
                if (cb_edit) {
                    jQuery("#divToolImg").css("top", _top + "px");
                    jQuery("#divToolImg").css("left", _left + "px");
                    jQuery("#divToolImg").stop(true, true).css({
                        display: 'none'
                    }).fadeIn(20);
                    jQuery("#divToolImgSettings").css("top", _top2 + "px");
                    jQuery("#divToolImgSettings").css("left", _left + "px");
                    jQuery("#divToolImgSettings").stop(true, true).css({
                        display: 'none'
                    }).fadeIn(20)
                }
                $imgActive = jQuery(this);
                jQuery("#divToolImg").unbind('click');
                jQuery("#divToolImg").bind('click', function (e) {
                    jQuery(this).data('image', $imgActive);
                    jQuery('input.my-file[type=file]').click();
                    e.preventDefault();
                    e.stopImmediatePropagation()
                });
                jQuery("#divToolImg").unbind('hover');
                jQuery("#divToolImg").hover(function (e) {
                    jQuery("#divToolImg").stop(true, true).css("display", "block");
                    jQuery("#divToolImgSettings").stop(true, true).css("display", "block")
                }, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0)
                });
                $element.find('figcaption').unbind('hover');
                $element.find('figcaption').hover(function (e) {
                    jQuery("#divToolImg").stop(true, true).css("display", "block");
                    jQuery("#divToolImgSettings").stop(true, true).css("display", "block")
                }, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0)
                });
                jQuery("#divToolImgSettings").unbind('hover');
                jQuery("#divToolImgSettings").hover(function (e) {
                    jQuery("#divToolImg").stop(true, true).css("display", "block");
                    jQuery("#divToolImgSettings").stop(true, true).css("display", "block")
                }, function () {
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0)
                });
                jQuery("#lnkImageSettings").unbind('click');
                jQuery("#lnkImageSettings").bind('click', function (e) {
                    jQuery(this).data('image', $imgActive);
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery('#md-img').css('width', '45%');
                    jQuery('#md-img').simplemodal();
                    jQuery('#md-img').data('simplemodal').show();
                    var $img = $element;
                    if ($element.prop("tagName").toLowerCase() == 'figure') {
                        $img = $element.find('img:first')
                    }
                    jQuery('#txtImgUrl').val($img.attr('src'));
                    jQuery('#txtAltText').val($img.attr('alt'));
                    jQuery('#txtLinkUrl').val('');
                    if ($img.parents('a:first') != undefined) {
                        jQuery('#txtLinkUrl').val($img.parents('a:first').attr('href'))
                    }
                    jQuery('#btnImgOk').unbind('click');
                    jQuery('#btnImgOk').bind('click', function (e) {
                        var builder;
                        $element.parents().each(function () {
                            if (jQuery(this).data('contentbuilder')) {
                                builder = jQuery(this).data('contentbuilder')
                            }
                        });
                        $img.attr('src', jQuery('#txtImgUrl').val());
                        $img.attr('alt', jQuery('#txtAltText').val());
                        if (jQuery('#txtLinkUrl').val() == 'http://' || jQuery('#txtLinkUrl').val() == '') {
                            $img.parents('a:first').replaceWith($img.parents('a:first').html())
                        } else {
                            if ($img.parents('a:first').length == 0) {
                                $img.wrap('<a href="' + jQuery('#txtLinkUrl').val() + '"></a>')
                            } else {
                                $img.parents('a:first').attr('href', jQuery('#txtLinkUrl').val())
                            }
                        }
                        if (builder) builder.applyBehavior();
                        jQuery('#md-img').data('simplemodal').hide()
                    });
                    e.preventDefault();
                    e.stopImmediatePropagation()
                });
                jQuery("#btnImageBrowse").unbind('click');
                jQuery("#btnImageBrowse").bind('click', function (e) {
                    jQuery('#ifrImageBrowse').attr('src', $element.data('imageembed').settings.imageselect);
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery("#divRteLink").stop(true, true).fadeOut(0);
                    jQuery("#divFrameLink").stop(true, true).fadeOut(0);
                    jQuery('#active-input').val('txtImgUrl');
                    jQuery('#md-imageselect').css('width', '65%');
                    jQuery('#md-imageselect').simplemodal();
                    jQuery('#md-imageselect').data('simplemodal').show()
                });
                jQuery("#btnFileBrowse").unbind('click');
                jQuery("#btnFileBrowse").bind('click', function (e) {
                    jQuery('#ifrFileBrowse').attr('src', $element.data('imageembed').settings.fileselect);
                    jQuery("#divToolImg").stop(true, true).fadeOut(0);
                    jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                    jQuery("#divRteLink").stop(true, true).fadeOut(0);
                    jQuery("#divFrameLink").stop(true, true).fadeOut(0);
                    jQuery('#active-input').val('txtLinkUrl');
                    jQuery('#md-fileselect').css('width', '65%');
                    jQuery('#md-fileselect').simplemodal();
                    jQuery('#md-fileselect').data('simplemodal').show()
                })
            }, function (e) {
                jQuery("#divToolImg").stop(true, true).fadeOut(0);
                jQuery("#divToolImgSettings").stop(true, true).fadeOut(0)
            })
        };
        var changeImage = function (e) {
                if (typeof FileReader == "undefined") return true;
                var elem = jQuery(this);
                var files = e.target.files;
                var hiquality = true;
                try {
                    hiquality = $element.data('imageembed').settings.hiquality
                } catch (e) {};
                for (var i = 0, file; file = files[i]; i++) {
                    var imgname = file.name;
                    var extension = imgname.substr((imgname.lastIndexOf('.') + 1)).toLowerCase();
                    if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif' || extension == 'bmp') {} else {
                        alert('Please select an image');
                        return
                    }
                    if (file.type.match('image.*')) {
                        jQuery("#divToolImg").stop(true, true).fadeOut(0);
                        jQuery("#divToolImgSettings").stop(true, true).fadeOut(0);
                        jQuery('.overlay-bg').css('width', '100%');
                        jQuery('.overlay-bg').css('height', '100%');
                        jQuery("#divToolImgLoader").css('top', jQuery('#divToolImg').css('top'));
                        jQuery("#divToolImgLoader").css('left', jQuery('#divToolImg').css('left'));
                        jQuery("#divToolImgLoader").css('display', 'block');
                        var reader = new FileReader();
                        reader.onload = (function (theFile) {
                            return function (e) {
                                var image = e.target.result;
                                $imgActive = jQuery("#divToolImg").data('image');
                                var zoom = localStorage.zoom;
                                if ($imgActive.prop("tagName").toLowerCase() == 'img') {
                                    jQuery("#my-mask").css('width', $imgActive.width() + 'px');
                                    jQuery("#my-mask").css('height', $imgActive.height() + 'px')
                                } else {
                                    jQuery("#my-mask").css('width', $imgActive.innerWidth() + 'px');
                                    jQuery("#my-mask").css('height', $imgActive.innerHeight() + 'px')
                                }
                                jQuery("#my-mask").css('zoom', zoom);
                                jQuery("#my-mask").css('-moz-transform', 'scale(' + zoom + ')');
                                var oimg = new Image();
                                oimg.onload = function (evt) {
                                    $imgActive = jQuery("#divToolImg").data('image');
                                    nInitialWidth = this.width;
                                    nInitialHeight = this.height;
                                    var newW;
                                    var newY;
                                    var maskWidth = $imgActive.width();
                                    var maskHeight = $imgActive.height();
                                    var photoAspectRatio = nInitialWidth / nInitialHeight;
                                    var canvasAspectRatio = maskWidth / maskHeight;
                                    if (photoAspectRatio < canvasAspectRatio) {
                                        newW = maskWidth;
                                        newY = (nInitialHeight * maskWidth) / nInitialWidth
                                    } else {
                                        newW = (nInitialWidth * maskHeight) / nInitialHeight;
                                        newY = maskHeight
                                    }
                                    this.width = newW;
                                    this.height = newY;
                                    jQuery('#my-image').attr('src', image);
                                    jQuery('#my-image').on('load', function () {
                                        jQuery('.overlay-bg').css('width', '100%');
                                        jQuery('.overlay-bg').css('height', '100%');
                                        $imgActive = jQuery("#divToolImg").data('image');
                                        jQuery("#my-image").css('top', '0px');
                                        jQuery("#my-image").css('left', '0px');
                                        jQuery("#my-image").css('width', newW + 'px');
                                        jQuery("#my-image").css('height', newY + 'px');
                                        var zoom = localStorage.zoom;
                                        zoom = zoom * 1;
                                        var _top;
                                        var _left;
                                        var _top_polaroid;
                                        var _left_polaroid;
                                        var scrolltop = jQuery(window).scrollTop();
                                        var offsettop = $imgActive.offset().top;
                                        var offsetleft = $imgActive.offset().left;
                                        var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                                        var is_ie = detectIE();
                                        var browserok = true;
                                        if (is_firefox || is_ie) browserok = false;
                                        if (browserok) {
                                            _top = (offsettop * zoom) + (scrolltop - scrolltop * zoom);
                                            _left = offsetleft * zoom;
                                            _top_polaroid = ((offsettop + 5) * zoom) + (scrolltop - scrolltop * zoom);
                                            _left_polaroid = (offsetleft + 5) * zoom
                                        } else {
                                            if (is_ie) {
                                                var space = 0;
                                                var space2 = 0;
                                                $element.parents().each(function () {
                                                    if (jQuery(this).data('contentbuilder')) {
                                                        space = jQuery(this).getPos().top;
                                                        space2 = jQuery(this).getPos().left
                                                    }
                                                });
                                                var adjy_val = -space * zoom + space;
                                                var adjx_val = -space2 * zoom + space2;
                                                var p = $imgActive.getPos();
                                                _top = (p.top * zoom) + adjy_val;
                                                _left = (p.left * zoom) + adjx_val;
                                                _top_polaroid = ((p.top + 5) * zoom) + adjy_val;
                                                _left_polaroid = ((p.left + 5) * zoom) + adjx_val
                                            }
                                            if (is_firefox) {
                                                var imgwidth = parseInt($imgActive.css('width'));
                                                var imgheight = parseInt($imgActive.css('height'));
                                                var adjx_val = imgwidth / 2 - (imgwidth / 2) * zoom;
                                                var adjy_val = imgheight / 2 - (imgheight / 2) * zoom;
                                                jQuery('#img-control').css('top', 5 + adjy_val + 'px');
                                                jQuery('#img-control').css('left', 7 + adjx_val + 'px');
                                                _top = offsettop - adjy_val;
                                                _left = offsetleft - adjx_val;
                                                _top_polaroid = offsettop - adjy_val + 5;
                                                _left_polaroid = offsetleft - adjx_val + 5
                                            }
                                        }
                                        jQuery('#divImageEdit').css('display', 'inline-block');
                                        if ($imgActive.attr('class') == 'img-polaroid') {
                                            jQuery("#divImageEdit").css("top", _top_polaroid + "px");
                                            jQuery("#divImageEdit").css("left", _left_polaroid + "px")
                                        } else {
                                            jQuery("#divImageEdit").css("top", _top + "px");
                                            jQuery("#divImageEdit").css("left", _left + "px")
                                        }
                                        panSetup();
                                        tmpCanvas.width = newW;
                                        tmpCanvas.height = newY;
                                        var imageObj = jQuery("#my-image")[0];
                                        var context = tmpCanvas.getContext('2d');
                                        var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                                        if (is_firefox) sleep(700);
                                        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
                                            try {
                                                var mpImg = new MegaPixImage(imageObj);
                                                mpImg.render(tmpCanvas, {
                                                    width: imageObj.width,
                                                    height: imageObj.height
                                                })
                                            } catch (e) {
                                                context.drawImage(imageObj, 0, 0, newW, newY)
                                            }
                                        } else {
                                            context.drawImage(imageObj, 0, 0, newW, newY)
                                        }
                                        crop();
                                        if ($imgActive.attr('class') == 'img-circle') {
                                            jQuery('#my-mask').css('-webkit-border-radius', '500px');
                                            jQuery('#my-mask').css('-moz-border-radius', '500px');
                                            jQuery('#my-mask').css('border-radius', '500px')
                                        } else {
                                            jQuery('#my-mask').css('-webkit-border-radius', '0px');
                                            jQuery('#my-mask').css('-moz-border-radius', '0px');
                                            jQuery('#my-mask').css('border-radius', '0px')
                                        }
                                        jQuery('#my-image').unbind('load');
                                        if ($imgActive.prop("tagName").toLowerCase() == 'img') {} else {
                                            jQuery('#btnZoomIn').click();
                                            jQuery('#btnZoomIn').click()
                                        }
                                        jQuery("#divToolImgLoader").css('display', 'none')
                                    });
                                    jQuery('#btnChangeImage').unbind('click');
                                    jQuery('#btnChangeImage').bind('click', function () {
                                        var canvas = document.getElementById('myCanvas');
                                        $imgActive = jQuery("#divToolImg").data('image');
                                        var image;
                                        if (hiquality == false) {
                                            if (extension == 'jpg' || extension == 'jpeg') {
                                                image = canvas.toDataURL("image/jpeg", 0.9)
                                            } else {
                                                image = canvas.toDataURL("image/png", 1)
                                            }
                                        } else {
                                            image = canvas.toDataURL("image/png", 1)
                                        }
                                        if ($imgActive.prop("tagName").toLowerCase() == 'img') {
                                            $imgActive.attr('src', image);
                                            $imgActive.data('filename', imgname)
                                        } else if ($imgActive.prop("tagName").toLowerCase() == 'figure') {
                                            $imgActive.find('img').attr('src', image);
                                            $imgActive.find('img').data('filename', imgname)
                                        } else {
                                            $imgActive.css('background-image', 'url(data:' + image + ')');
                                            $imgActive.data('filename', imgname)
                                        }
                                        jQuery('#divImageEdit').css('display', 'none');
                                        jQuery('.overlay-bg').css('width', '1px');
                                        jQuery('.overlay-bg').css('height', '1px')
                                    });
                                    jQuery('#btnImageCancel').unbind('click');
                                    jQuery('#btnImageCancel').bind('click', function () {
                                        var canvas = document.getElementById('myCanvas');
                                        $imgActive = jQuery("#divToolImg").data('image');
                                        jQuery('#divImageEdit').css('display', 'none');
                                        jQuery('.overlay-bg').css('width', '1px');
                                        jQuery('.overlay-bg').css('height', '1px')
                                    });
                                    jQuery('#btnZoomIn').unbind('click');
                                    jQuery('#btnZoomIn').bind('click', function () {
                                        var nCurrentWidth = parseInt(jQuery("#my-image").css('width'));
                                        var nCurrentHeight = parseInt(jQuery("#my-image").css('height'));
                                        jQuery("#my-image").css('width', (nCurrentWidth / 0.9) + 'px');
                                        jQuery("#my-image").css('height', (nCurrentHeight / 0.9) + 'px');
                                        panSetup();
                                        tmpCanvas.width = (nCurrentWidth / 0.9);
                                        tmpCanvas.height = (nCurrentHeight / 0.9);
                                        var imageObj = jQuery("#my-image")[0];
                                        var context = tmpCanvas.getContext('2d');
                                        context.drawImage(imageObj, 0, 0, (nCurrentWidth / 0.9), (nCurrentHeight / 0.9));
                                        crop()
                                    });
                                    jQuery('#btnZoomOut').unbind('click');
                                    jQuery('#btnZoomOut').bind('click', function () {
                                        var nCurrentWidth = parseInt(jQuery("#my-image").css('width'));
                                        var nCurrentHeight = parseInt(jQuery("#my-image").css('height'));
                                        jQuery("#my-image").css('width', (nCurrentWidth / 1.1) + 'px');
                                        jQuery("#my-image").css('height', (nCurrentHeight / 1.1) + 'px');
                                        panSetup();
                                        tmpCanvas.width = (nCurrentWidth / 1.1);
                                        tmpCanvas.height = (nCurrentHeight / 1.1);
                                        var imageObj = jQuery("#my-image")[0];
                                        var context = tmpCanvas.getContext('2d');
                                        context.drawImage(imageObj, 0, 0, (nCurrentWidth / 1.1), (nCurrentHeight / 1.1));
                                        crop()
                                    })
                                };
                                oimg.src = image
                            }
                        })(file);
                        reader.readAsDataURL(file)
                    }
                }
            };
        var crop = function () {
                var x = parseInt(jQuery("#my-image").css('left'));
                var y = parseInt(jQuery("#my-image").css('top'));
                var dw = parseInt(jQuery("#my-mask").css('width'));
                var dh = parseInt(jQuery("#my-mask").css('height'));
                var canvas = document.getElementById('myCanvas');
                var context = canvas.getContext('2d');
                canvas.width = dw;
                canvas.height = dh;
                var imageObj = jQuery("#my-image")[0];
                var sourceX = -1 * x;
                var sourceY = -1 * y;
                if (sourceY > (tmpCanvas.height - dh)) sourceY = tmpCanvas.height - dh;
                if (sourceX > (tmpCanvas.width - dw)) sourceX = tmpCanvas.width - dw;
                context.drawImage(tmpCanvas, sourceX, sourceY, dw, dh, 0, 0, dw, dh)
            };
        var panSetup = function () {
                jQuery("#my-image").css({
                    top: 0,
                    left: 0
                });
                var maskWidth = jQuery("#my-mask").width();
                var maskHeight = jQuery("#my-mask").height();
                var imgPos = jQuery("#my-image").offset();
                var imgWidth = jQuery("#my-image").width();
                var imgHeight = jQuery("#my-image").height();
                var x1 = (imgPos.left + maskWidth) - imgWidth;
                var y1 = (imgPos.top + maskHeight) - imgHeight;
                var x2 = imgPos.left;
                var y2 = imgPos.top;
                jQuery("#my-image").draggable({
                    revert: false,
                    containment: [x1, y1, x2, y2],
                    drag: function () {
                        crop()
                    }
                });
                jQuery("#my-image").css({
                    cursor: 'move'
                })
            };
        this.init()
    };
    jQuery.fn.imageembed = function (options) {
        return this.each(function () {
            if (undefined == jQuery(this).data('imageembed')) {
                var plugin = new jQuery.imageembed(this, options);
                jQuery(this).data('imageembed', plugin)
            }
        })
    }
})(jQuery);

function makeid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < 5; i++) text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text
}
function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break
        }
    }
}
jQuery.fn.clearFields = jQuery.fn.clearInputs = function (includeHidden) {
    var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
    return this.each(function () {
        var t = this.type,
            tag = this.tagName.toLowerCase();
        if (re.test(t) || tag == 'textarea') {
            this.value = ''
        } else if (t == 'checkbox' || t == 'radio') {
            this.checked = false
        } else if (tag == 'select') {
            this.selectedIndex = -1
        } else if (t == "file") {
            if (/MSIE/.test(navigator.userAgent)) {
                jQuery(this).replaceWith(jQuery(this).clone(true))
            } else {
                jQuery(this).val('')
            }
        } else if (includeHidden) {
            if ((includeHidden === true && /hidden/.test(t)) || (typeof includeHidden == 'string' && jQuery(this).is(includeHidden))) this.value = ''
        }
    })
};
(function (jQuery) {
    jQuery.simplemodal = function (element, options) {
        var defaults = {
            onCancel: function () {}
        };
        this.settings = {};
        var $element = jQuery(element),
            element = element;
        var $ovlid;
        this.init = function () {
            this.settings = jQuery.extend({}, defaults, options);
            if (jQuery('#divCb').length == 0) {
                jQuery('body').append('<div id="divCb"></div>')
            }
        };
        this.hide = function () {
            $element.css('display', 'none');
            $element.removeClass('md-show');
            $ovlid.remove()
        };
        this.show = function () {
            var rnd = makeid();
            var html_overlay = '<div id="md-overlay-' + rnd + '" class="md-overlay"></div>';
            jQuery('#divCb').append(html_overlay);
            $ovlid = jQuery('#md-overlay-' + rnd);
            $element.addClass('md-show');
            $element.stop(true, true).css('display', 'none').fadeIn(300);
            jQuery('#md-overlay-' + rnd).unbind();
            jQuery('#md-overlay-' + rnd).click(function () {
                $element.stop(true, true).fadeOut(300, function () {
                    $element.removeClass('md-show')
                });
                $ovlid.remove();
                $element.data('simplemodal').settings.onCancel()
            })
        };
        this.init()
    };
    jQuery.fn.simplemodal = function (options) {
        return this.each(function () {
            if (undefined == jQuery(this).data('simplemodal')) {
                var plugin = new jQuery.simplemodal(this, options);
                jQuery(this).data('simplemodal', plugin)
            }
        })
    }
})(jQuery);
jQuery.fn.getPos = function () {
    var o = this[0];
    var left = 0,
        top = 0,
        parentNode = null,
        offsetParent = null;
    offsetParent = o.offsetParent;
    var original = o;
    var el = o;
    while (el.parentNode != null) {
        el = el.parentNode;
        if (el.offsetParent != null) {
            var considerScroll = true;
            if (window.opera) {
                if (el == original.parentNode || el.nodeName == "TR") {
                    considerScroll = false
                }
            }
            if (considerScroll) {
                if (el.scrollTop && el.scrollTop > 0) {
                    top -= el.scrollTop
                }
                if (el.scrollLeft && el.scrollLeft > 0) {
                    left -= el.scrollLeft
                }
            }
        }
        if (el == offsetParent) {
            left += o.offsetLeft;
            if (el.clientLeft && el.nodeName != "TABLE") {
                left += el.clientLeft
            }
            top += o.offsetTop;
            if (el.clientTop && el.nodeName != "TABLE") {
                top += el.clientTop
            }
            o = el;
            if (o.offsetParent == null) {
                if (o.offsetLeft) {
                    left += o.offsetLeft
                }
                if (o.offsetTop) {
                    top += o.offsetTop
                }
            }
            offsetParent = o.offsetParent
        }
    }
    return {
        left: left,
        top: top
    }
};

function cleanHTML(input) {
    var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
    var output = input.replace(stringStripper, ' ');
    var commentSripper = new RegExp('<!--(.*?)-->', 'g');
    var output = output.replace(commentSripper, '');
    var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>', 'gi');
    output = output.replace(tagStripper, '');
    var badTags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];
    for (var i = 0; i < badTags.length; i++) {
        tagStripper = new RegExp('<' + badTags[i] + '.*?' + badTags[i] + '(.*?)>', 'gi');
        output = output.replace(tagStripper, '')
    }
    var badAttributes = ['style', 'start'];
    for (var i = 0; i < badAttributes.length; i++) {
        var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"', 'gi');
        output = output.replace(attributeStripper, '')
    }
    return output
}
function detectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    var trident = ua.indexOf('Trident/');
    if (msie > 0) {
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10)
    }
    if (trident > 0) {
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10)
    }
    return false
};

/*! rangeslider.js - v0.3.1 | (c) 2014 @andreruffert | MIT license | https://github.com/andreruffert/rangeslider.js */
eval(function (p, a, c, k, e, r) {
    e = function (c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) r[e(c)] = k[c] || e(c);
        k = [function (e) {
            return r[e]
        }];
        e = function () {
            return '\\w+'
        };
        c = 1
    };
    while (c--) if (k[c]) p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]);
    return p
}('\'1Z 2c\';(4(13){7(v 1j===\'4\'&&1j.28){1j([\'1K\'],13)}1h 7(v 2d===\'2r\'){13(2t(\'1K\'))}1h{13(d)}}(4(d){4 1E(){f Z=o.2u(\'Z\');Z.2S(\'1w\',\'h\');9 Z.1w!==\'2m\'}f 8=\'18\',M=[],1r=1E(),17={O:1T,1Q:\'18\',U:\'18--1F\',1v:\'2D\',15:\'1W\',z:[\'23\',\'25\',\'27\'],A:[\'29\',\'2a\',\'2b\'],B:[\'2e\',\'2f\',\'2l\']};4 1o(k,1m){f Q=1G.a.1C.1X(1A,2);9 1u(4(){9 k.19(1M,Q)},1m)}4 1x(k,V){V=V||1q;9 4(){7(!k.14){f Q=1G.a.1C.19(1A);k.1B=k.19(N,Q);k.14=1T}2i(k.1P);k.1P=1u(4(){k.14=1n},V);9 k.1B}}4 b(c,6){3.$N=d(N);3.$o=d(o);3.$c=d(c);3.6=d.2E({},17,6);3.2H=17;3.2R=8;3.z=3.6.z.1f(\'.\'+8+\' \')+\'.\'+8;3.A=3.6.A.1f(\'.\'+8+\' \')+\'.\'+8;3.B=3.6.B.1f(\'.\'+8+\' \')+\'.\'+8;3.O=3.6.O;3.I=3.6.I;3.G=3.6.G;3.C=3.6.C;7(3.O){7(1r){9 1n}}3.R=\'24-\'+8+\'-\'+(+1U 26());3.l=S(3.$c[0].1k(\'l\')||0);3.q=S(3.$c[0].1k(\'q\')||1q);3.5=S(3.$c[0].5||3.l+(3.q-3.l)/2);3.u=S(3.$c[0].1k(\'u\')||1);3.$1a=d(\'<1b 1c="\'+3.6.1v+\'" />\');3.$K=d(\'<1b 1c="\'+3.6.15+\'" />\');3.$h=d(\'<1b 1c="\'+3.6.1Q+\'" 2n="\'+3.R+\'" />\').2o(3.$c).2p(3.$1a,3.$K);3.$c.2q({\'T\':\'2s\',\'1H\':\'1I\',\'2v\':\'1I\',\'2w\':\'2x\',\'2y\':\'0\'});3.J=d.1g(3.J,3);3.H=d.1g(3.H,3);3.F=d.1g(3.F,3);3.1l();f P=3;3.$N.E(\'1Y\'+\'.\'+8,1x(4(){1o(4(){P.16()},21)},20));3.$o.E(3.z,\'#\'+3.R+\':22(.\'+3.6.U+\')\',3.J);3.$c.E(\'1p\'+\'.\'+8,4(e,m){7(m&&m.1s===8){9}f 5=e.1t.5,j=P.W(5);P.D(j)})}b.a.1l=4(){7(3.I&&v 3.I===\'4\'){3.I()}3.16()};b.a.16=4(){3.X=3.$K[0].1y;3.1z=3.$h[0].1y;3.Y=3.1z-3.X;3.w=3.X/2;3.T=3.W(3.5);7(3.$c[0].1F){3.$h.2g(3.6.U)}1h{3.$h.2h(3.6.U)}3.D(3.T)};b.a.J=4(e){e.1d();3.$o.E(3.A,3.H);3.$o.E(3.B,3.F);7((\' \'+e.1t.2j+\' \').2k(/[\\n\\t]/g,\' \').1D(3.6.15)>-1){9}f p=3.10(3.$h[0],e),11=3.12(3.$K[0])-3.12(3.$h[0]);3.D(p-3.w);7(p>=11&&p<11+3.X){3.w=p-11}};b.a.H=4(e){e.1d();f p=3.10(3.$h[0],e);3.D(p-3.w)};b.a.F=4(e){e.1d();3.$o.L(3.A,3.H);3.$o.L(3.B,3.F);f p=3.10(3.$h[0],e);7(3.C&&v 3.C===\'4\'){3.C(p-3.w,3.5)}};b.a.1J=4(j,l,q){7(j<l){9 l}7(j>q){9 q}9 j};b.a.D=4(j){f 5,r;5=(3.1L(3.1J(j,0,3.Y))/3.u)*3.u;r=3.W(5);3.$1a[0].1i.1H=(r+3.w)+\'1N\';3.$K[0].1i.r=r+\'1N\';3.1O(5);3.T=r;3.5=5;7(3.G&&v 3.G===\'4\'){3.G(r,5)}};b.a.12=4(s){f i=0;2z(s!==1M){i+=s.2A;s=s.2B}9 i};b.a.10=4(s,e){9(e.2C||e.1R.1S||e.1R.2F[0].1S||e.2G.x)-3.12(s)};b.a.W=4(5){f y,j;y=(5-3.l)/(3.q-3.l);j=y*3.Y;9 j};b.a.1L=4(j){f y,5;y=((j)/(3.Y||1));5=3.u*2I.2J((((y)*(3.q-3.l))+3.l)/3.u);9 2K((5).2L(2))};b.a.1O=4(5){7(5!==3.5){3.$c.2M(5).2N(\'1p\',{1s:8})}};b.a.2O=4(){3.$o.L(3.z,\'#\'+3.R,3.J);3.$c.L(\'.\'+8).2P(\'1i\').2Q(\'1e\'+8);7(3.$h&&3.$h.1V){3.$h[0].2T.2U(3.$h[0])}M.2V(M.1D(3.$c[0]),1);7(!M.1V){3.$N.L(\'.\'+8)}};d.k[8]=4(6){9 3.2W(4(){f $3=d(3),m=$3.m(\'1e\'+8);7(!m){$3.m(\'1e\'+8,(m=1U b(3,6)));M.2X(3)}7(v 6===\'2Y\'){m[6]()}})}}));', 62, 185, '|||this|function|value|options|if|pluginName|return|prototype|Plugin|element|jQuery||var||range||pos|fn|min|data||document|posX|max|left|node||step|typeof|grabX||percentage|startEvent|moveEvent|endEvent|onSlideEnd|setPosition|on|handleEnd|onSlide|handleMove|onInit|handleDown|handle|off|pluginInstances|window|polyfill|_this|args|identifier|parseFloat|position|disabledClass|debounceDuration|getPositionFromValue|handleWidth|maxHandleX|input|getRelativePosition|handleX|getPositionFromNode|factory|debouncing|handleClass|update|defaults|rangeslider|apply|fill|div|class|preventDefault|plugin_|join|proxy|else|style|define|getAttribute|init|wait|false|delay|change|100|inputrange|origin|target|setTimeout|fillClass|type|debounce|offsetWidth|rangeWidth|arguments|lastReturnVal|slice|indexOf|supportsRange|disabled|Array|width|1px|cap|jquery|getValueFromPosition|null|px|setValue|debounceTimeout|rangeClass|originalEvent|clientX|true|new|length|rangeslider__handle|call|resize|use||300|not|mousedown|js|touchstart|Date|pointerdown|amd|mousemove|touchmove|pointermove|strict|exports|mouseup|touchend|addClass|removeClass|clearTimeout|className|replace|pointerup|text|id|insertAfter|prepend|css|object|absolute|require|createElement|height|overflow|hidden|opacity|while|offsetLeft|offsetParent|pageX|rangeslider__fill|extend|touches|currentPoint|_defaults|Math|ceil|Number|toFixed|val|trigger|destroy|removeAttr|removeData|_name|setAttribute|parentNode|removeChild|splice|each|push|string'.split('|'), 0, {}));

/*! jQuery UI Touch Punch 0.2.3 | Copyright 2011â€“2014, Dave Furfero | Dual licensed under the MIT or GPL Version 2 licenses. */
eval(function (p, a, c, k, e, r) {
    e = function (c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) r[e(c)] = k[c] || e(c);
        k = [function (e) {
            return r[e]
        }];
        e = function () {
            return '\\w+'
        };
        c = 1
    };
    while (c--) if (k[c]) p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]);
    return p
}('(7(4){4.w.8=\'H\'G p;c(!4.w.8){f}d 6=4.U.D.L,g=6.g,h=6.h,a;7 5(2,r){c(2.k.F.J>1){f}2.B();d 8=2.k.q[0],l=p.N(\'O\');l.S(r,i,i,V,1,8.W,8.X,8.Y,8.A,b,b,b,b,0,C);2.z.E(l)}6.m=7(2){d 3=e;c(a||!3.I(2.k.q[0])){f}a=i;3.j=b;5(2,\'K\');5(2,\'s\');5(2,\'M\')};6.n=7(2){c(!a){f}e.j=i;5(2,\'s\')};6.o=7(2){c(!a){f}5(2,\'P\');5(2,\'Q\');c(!e.j){5(2,\'R\')}a=b};6.g=7(){d 3=e;3.u.T({v:4.9(3,\'m\'),x:4.9(3,\'n\'),y:4.9(3,\'o\')});g.t(3)};6.h=7(){d 3=e;3.u.Z({v:4.9(3,\'m\'),x:4.9(3,\'n\'),y:4.9(3,\'o\')});h.t(3)}})(4);', 62, 62, '||event|self|jQuery|simulateMouseEvent|mouseProto|function|touch|proxy|touchHandled|false|if|var|this|return|_mouseInit|_mouseDestroy|true|_touchMoved|originalEvent|simulatedEvent|_touchStart|_touchMove|_touchEnd|document|changedTouches|simulatedType|mousemove|call|element|touchstart|support|touchmove|touchend|target|clientY|preventDefault|null|mouse|dispatchEvent|touches|in|ontouchend|_mouseCapture|length|mouseover|prototype|mousedown|createEvent|MouseEvents|mouseup|mouseout|click|initMouseEvent|bind|ui|window|screenX|screenY|clientX|unbind'.split('|'), 0, {}));