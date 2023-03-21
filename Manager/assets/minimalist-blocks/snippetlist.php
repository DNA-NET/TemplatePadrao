﻿<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php"; ?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
	<script>
		var pasta_inicial = '/atualizadxp/Manager/'
	</script>
    <script src="content.js?104" type="text/javascript"></script>

	<?php 
	//====================================================================
	// Elementos ou Blocos de conteúdo personalizados
	//====================================================================

	$RSElemento = db_query($con,"select ELEMENTO_ID, ELEMENTO_NOME, Elemento_categoria, Elemento FROM elemento where Elemento_status = 'Ativo' ORDER BY Elemento_categoria");
	If(db_num_rows($RSElemento) > 0){
		echo '<script>';
		while($RSE=db_fetch_array($RSElemento)) {

			$categoria = explode(",", $RSE["Elemento_categoria"]);

			echo "data_basic.snippets.push(";
			echo "{  'thumbnail': 'show_image.php?show_arquivo=elemento&show_campo=ELEMENTO_THUMB&show_chave=ELEMENTO_ID=" . $RSE["ELEMENTO_ID"] . "',";
			echo "			'category': '" . $categoria[0] . "',";
			echo "			'html':";
			echo "				'" . $RSE["Elemento"] . "'";
			echo "} );";

		}
		echo '</script>';

	}
	?>
    <style>
        body {
            background: #fff;
            margin: 0;
        }

        .is-design-list {
            position: fixed;
            top: 0px;
            left: 0px;
            border-top: transparent 80px solid;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            padding: 0px 0px 30px 30px;
            box-sizing: border-box;
            overflow: auto;
        }

        .is-design-list>div {
            width: 250px;
            overflow: hidden;
            /* background: #000; */
            margin: 32px 40px 0 0;
            cursor: pointer;
            display: inline-block;
            outline: #ececec 1px solid;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        .is-design-list>div img {
            box-shadow: none;
            opacity: 1;
            display: block;
            box-sizing: border-box;
            transition: all 0.2s ease-in-out;
            max-width: 400px;
            width: 100%
        }

        .is-design-list>div:hover img {
            opacity: 0.95;
        }
        
        .is-design-list>div:hover {
            background: #999;
        }

        .is-category-list {
            position: relative;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 80px;
            box-sizing: border-box;
            z-index: 1;
        }

        .is-category-list>div {
            white-space: nowrap;
            padding: 0 30px;
            box-sizing: border-box;
            font-family: sans-serif;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: #f5f5f5;
        }

        .is-category-list a {
            display: inline-block;
            padding: 10px 20px;
            background: #fefefe;
            color: #000;
            border-radius: 50px;

            margin: 0 12px 0 0;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            transition: box-shadow ease 0.3s;
        }

        .is-category-list a:hover {
            /*background: #fafafa;*/
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
            color: #000;
        }

        .is-category-list a.active {
            background: #f5f5f5;
            color: #000;
            box-shadow: none;
            cursor: default;
        }

        .is-more-categories {
            display: none;
            position: absolute;
            width: 400px;
            box-sizing: border-box;
            padding: 0;
            z-index: 1;
            font-family: sans-serif;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        .is-more-categories a {
            width: 200px;
            float: left;
            display: block;
            box-sizing: border-box;
            padding: 12px 20px;
            background: #fff;
            text-decoration: none;
            color: #000;
            line-height: 1.6;
        }

        .is-more-categories a:hover {
            background: #eee;
        }

        .is-more-categories a.active {
            background: #eee;
        }

        .is-more-categories.active {
            display: block;
        }

        /* First Loading */
        /* .is-category-list {
            display: none;
        }

        .is-design-list {
            display: none;
        }

        .pace {
            -webkit-pointer-events: none;
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        .pace-inactive {
            display: none;
        }

        .pace .pace-progress {
            background: #000000;
            position: fixed;
            z-index: 2000;
            top: 0;
            right: 100%;
            width: 100%;
            height: 2px;
        } */

        .is-more-categories>a:nth-child(0) {
            display: none
        }

        .is-more-categories>a:nth-child(1) {
            display: none
        }

        .is-more-categories>a:nth-child(2) {
            display: none
        }

        .is-more-categories>a:nth-child(3) {
            display: none
        }

        .is-more-categories>a:nth-child(4) {
            display: none
        }

        .is-more-categories>a:nth-child(5) {
            display: none
        }

        .is-more-categories>a:nth-child(6) {
            display: none
        }

        .is-more-categories>a:nth-child(7) {
            display: none
        }

        @media all and (max-width: 1212px) {
            .is-categories>a:nth-child(7):not(.more-snippets) {
                display: none
            }

            .is-more-categories>a:nth-child(7) {
                display: block
            }
        }

        @media all and (max-width: 1070px) {
            .is-categories>a:nth-child(6):not(.more-snippets) {
                display: none
            }

            .is-more-categories>a:nth-child(6) {
                display: block
            }
        }

        @media all and (max-width: 940px) {
            .is-categories>a:nth-child(5):not(.more-snippets) {
                display: none
            }

            .is-more-categories>a:nth-child(5) {
                display: block
            }
        }

        @media all and (max-width: 700px) {
            .is-categories>a:nth-child(4):not(.more-snippets) {
                display: none
            }

            .is-more-categories>a:nth-child(4) {
                display: block
            }
        }

        @media all and (max-width: 555px) {
            .is-categories>a:nth-child(3):not(.more-snippets) {
                display: none
            }

            .is-more-categories>a:nth-child(3) {
                display: block
            }
        }

        @media all and (max-width: 415px) {
            .is-categories>a:nth-child(2):not(.more-snippets) {
                display: none
            }

            .is-more-categories>a:nth-child(2) {
                display: block
            }
        }

        @media all and (max-width: 640px) {
            .is-more-categories a {
                width: 150px;
                padding: 10px 5px 10px 15px;
                font-size: 10px;
            }

            .is-more-categories {
                left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <svg style="display:none">
        <defs>
            <symbol viewBox="0 0 512 512" id="ion-ios-close-empty">
                <path d="M340.2 160l-84.4 84.3-84-83.9-11.8 11.8 84 83.8-84 83.9 11.8 11.7 84-83.8 84.4 84.2 11.8-11.7-84.4-84.3 84.4-84.2z"></path>
            </symbol>
        </defs>
    </svg>

    <div class="is-pop-close"
        style="z-index:10;width:30px;height:30px;position:absolute;top:0px;right:0px;box-sizing:border-box;padding:0;line-height:40px;font-size: 12px;color:#777;text-align:center;cursor:pointer;">
        <svg class="is-icon-flex" style="fill:rgba(0, 0, 0, 0.47);width:30px;height:30px;">
            <use xlink:href="#ion-ios-close-empty"></use>
        </svg>
    </div>

    <div class="is-category-list">
        <div class="is-categories" style="position:fixed;top:0;left:0;right:0;height:68px;padding-top:17px;box-sizing:border-box;">
            <!-- <a href="" data-cat="120" class="active">Basic</a>
            <a href="" data-cat="118">Article</a>
            <a href="" data-cat="101">Headline</a>
            <a href="" data-cat="119">Buttons</a>
            <a href="" data-cat="102">Photos</a>
            <a href="" data-cat="103">Profile</a>
            <a href="" data-cat="116">Contact</a>
            <a href="" class="more-snippets">More</a> -->
        </div>
    </div>
    <div class="is-more-categories">
        <!-- <a href="" data-cat="120" class="active">Basic</a>
        <a href="" data-cat="118">Article</a>
        <a href="" data-cat="101">Headline</a>
        <a href="" data-cat="119">Buttons</a>
        <a href="" data-cat="102">Photos</a>
        <a href="" data-cat="103">Profile</a>
        <a href="" data-cat="116">Contact</a>
        <a href="" data-cat="104">Products</a>
        <a href="" data-cat="105">Features</a>
        <a href="" data-cat="106">Process</a>
        <a href="" data-cat="107">Pricing</a>
        <a href="" data-cat="108">Skills</a>
        <a href="" data-cat="109">Achievements</a>
        <a href="" data-cat="110">Quotes</a>
        <a href="" data-cat="111">Partners</a>
        <a href="" data-cat="112">As Featured On</a>
        <a href="" data-cat="113">Page Not Found</a>
        <a href="" data-cat="114">Coming Soon</a>
        <a href="" data-cat="115">Help, FAQ</a> -->
    </div>

    <div class="is-design-list">
    </div>

    <script>

        var snippetPath = parent._cb.opts.snippetPath;
        var snippetCategories = parent._cb.opts.snippetCategories;
        var defaultSnippetCategory = parent._cb.opts.defaultSnippetCategory;

        var numOfCat = snippetCategories.length;
        if (numOfCat <= 7) {
            document.querySelector('.is-more-categories').style.width = '200px';
        }

        var categorytabs = document.querySelector('.is-categories');
        categorytabs.innerHTML = '';
        let html_catselect = '';
        for (var i = 0; i < numOfCat; i++) {
            if (i < 7) {
                html_catselect += '<a href="" data-cat="' + snippetCategories[i][0] + '">' + snippetCategories[i][1] + '</a>';
            }
        }
        html_catselect += '<a href="" class="more-snippets">' + parent._cb.out('More') + '</a>';
        categorytabs.innerHTML = html_catselect;

        var categorymore = document.querySelector('.is-more-categories');
        html_catselect = '';
        for (var i = 0; i < numOfCat; i++) {
            html_catselect += '<a href="" data-cat="' + snippetCategories[i][0] + '">' + snippetCategories[i][1] + '</a>';
        }
        categorymore.innerHTML = html_catselect;

        // Show/hide "More" button
        if (numOfCat <= 7) {
            var bHasMore = false;

            const childNodes = categorymore.childNodes;
            let i = childNodes.length;
            while (i--) {
                if(childNodes[i].style.display === 'block') {
                    bHasMore = true;
                }
            }
            var more = document.querySelector('.more-snippets');
            if (!bHasMore) more.style.display = 'none';
            else more.style.display = '';
        }

        /*
        jQuery(window).on('resize', function (e) {
            var bHasMore = false;
            jQuery('.is-more-categories').children().each(function () {
                if (jQuery(this).css('display') == 'block') {
                    bHasMore = true;
                }
            });
            if (!bHasMore) jQuery('.more-snippets').css('display', 'none');
            else jQuery('.more-snippets').css('display', '');
        });*/

        var elms = categorytabs.querySelectorAll('a[data-cat="' + defaultSnippetCategory + '"]'); //.classList.add('active');
        Array.prototype.forEach.call(elms, function(elm){
            elm.classList.add('active');
        });
        elms = categorymore.querySelectorAll('a[data-cat="' + defaultSnippetCategory + '"]'); //.classList.add('active');
        Array.prototype.forEach.call(elms, function(elm){
            elm.classList.add('active');
        });

        var snippets = data_basic.snippets; //DATA

        // Hide slider snippet if slick is not included
        var bHideSliderSnippet = true;
        if(parent.jQuery) {
            if(parent.jQuery.fn.slick) {
                bHideSliderSnippet = false;
            }
        }
        for (var nIndex = 0; nIndex < data_basic.snippets.length; nIndex++) {
            if (data_basic.snippets[nIndex].thumbnail.indexOf('element-slider.png') != -1 && bHideSliderSnippet) {
                data_basic.snippets.splice(nIndex, 1);
                break;
            }
        }
        
        var designlist = document.querySelector('.is-design-list');
        for (let i = 0; i <snippets.length; i++) {
            
            snippets[i].id = i+1;
            var thumb = snippets[i].thumbnail;

            thumb = snippetPath + thumb;

            if (snippets[i].category === defaultSnippetCategory + '') {
                designlist.insertAdjacentHTML('beforeend', '<div data-id="' + snippets[i].id + '" data-cat="' + snippets[i].category + '"><img src="' + thumb + '"></div>');
            
                var newitem = designlist.querySelector('[data-id="' + snippets[i].id + '"]');
                newitem.addEventListener('click', function(e){

                    var snippetid = e.target.parentNode.getAttribute('data-id');
                    addSnippet(snippetid);

                });

            }

        }

        var categorylist = document.querySelector('.is-category-list');
        elms = categorylist.querySelectorAll('a');
        Array.prototype.forEach.call(elms, function(elm){

            elm.addEventListener('click', function(e){

                if(elm.classList.contains('active')) return false;

                var cat = elm.getAttribute('data-cat');
                if(designlist.querySelectorAll('[data-cat="' + cat + '"]').length === 0) {

                    for (let i = 0; i <snippets.length; i++) {
                
                        var thumb = snippets[i].thumbnail;
                        
                        thumb = snippetPath + thumb;    

                        if (snippets[i].category === cat) {
                            designlist.insertAdjacentHTML('beforeend', '<div data-id="' + snippets[i].id + '" data-cat="' + snippets[i].category + '"><img src="' + thumb + '"></div>');
                        
                            var newitem = designlist.querySelector('[data-id="' + snippets[i].id + '"]');
                            newitem.addEventListener('click', function(e){
                                
                                var snippetid = e.target.parentNode.getAttribute('data-id');
                                addSnippet(snippetid);

                            });
                        }

                    }    
                }

                if (cat) {
                    // Hide all, show items from selected category
                    var categorylist_items = categorylist.querySelectorAll('a');    
                    Array.prototype.forEach.call(categorylist_items, function(elm){
                        elm.className = elm.className.replace('active', '');
                    });
                    categorymore.className = categorymore.className.replace('active', ''); 
                    var categorymore_items = categorymore.querySelectorAll('a');
                    Array.prototype.forEach.call(categorymore_items, function(elm){
                        elm.className = elm.className.replace('active', '');
                    });

                    var items = designlist.querySelectorAll('div');
                    Array.prototype.forEach.call(items, function(elm){
                        elm.style.display = 'none';
                    });
                    Array.prototype.forEach.call(items, function(elm){
                        var catSplit = elm.getAttribute('data-cat').split(',');
                        for (var j = 0; j < catSplit.length; j++) {
                            if (catSplit[j] == cat) {
                                elm.style.display = ''; // TODO: hide & show snippets => animated
                            }
                        }
                    });
                    
                } else {
                    // more snipptes
                    var more = document.querySelector('.more-snippets');
                    var moreCategories = document.querySelector('.is-more-categories');
                    if(more.classList.contains('active')) {
                        more.className = more.className.replace('active', '');
                        moreCategories.className = moreCategories.className.replace('active', '');
                    } else {
                        var _width = moreCategories.offsetWidth;
                        more.classList.add('active');
                        moreCategories.classList.add('active');
                        var top = more.getBoundingClientRect().top;
                        var left = more.getBoundingClientRect().left;
                        top = top + 50;
                        moreCategories.style.top = top + 'px';
                        moreCategories.style.left = left + 'px';
                    }
                }
                elm.classList.add('active');

                e.preventDefault();
            });

        });

        elms = categorymore.querySelectorAll('a');
        Array.prototype.forEach.call(elms, function(elm){

            elm.addEventListener('click', function(e){
                
                var cat = elm.getAttribute('data-cat');
                if(designlist.querySelectorAll('[data-cat="' + cat + '"]').length === 0) {

                    for (let i = 0; i <snippets.length; i++) {
                
                        var thumb = snippets[i].thumbnail;
                        
                        thumb = snippetPath + thumb;

                        if (snippets[i].category === cat) {
              
                            designlist.insertAdjacentHTML('beforeend', '<div data-id="' + snippets[i].id + '" data-cat="' + snippets[i].category + '"><img src="' + thumb + '"></div>');
                        
                            var newitem = designlist.querySelector('[data-id="' + snippets[i].id + '"]');
                            newitem.addEventListener('click', function(e){
                                
                                var snippetid = e.target.parentNode.getAttribute('data-id');
                                addSnippet(snippetid);

                            });
                        }

                    }    
                }

                // Hide all, show items from selected category
                Array.prototype.forEach.call(elms, function(elm){
                    elm.className = elm.className.replace('active', '');
                });
                categorymore.className = categorymore.className.replace('active', ''); // hide popup
                //var categorymore_items = categorymore.querySelectorAll('a');
                
                var categorylist = document.querySelector('.is-category-list');
                var categorylist_items = categorylist.querySelectorAll('a');                
                Array.prototype.forEach.call(categorylist_items, function(elm){
                    elm.className = elm.className.replace('active', '');
                });
                    
                var more = document.querySelector('.more-snippets');
                more.className = more.className.replace('active', '');

                var items = designlist.querySelectorAll('div');
                Array.prototype.forEach.call(items, function(elm){
                    elm.style.display = 'none';
                });
                Array.prototype.forEach.call(items, function(elm){
                    var catSplit = elm.getAttribute('data-cat').split(',');
                    for (var j = 0; j < catSplit.length; j++) {
                        if (catSplit[j] == cat) {
                            elm.style.display = '';
                        }
                    }
                });

                elm.classList.add('active');

                e.preventDefault();
            });

        });

        
        var close = document.querySelector('.is-pop-close');
        close.addEventListener('click', function(e){
            var modal = parent.document.querySelector('.is-modal.snippets');
            removeClass(modal, 'active');
        });

        // Add document Click event
        document.addEventListener('click', function(e){
            e = e || window.event;
            var target = e.target || e.srcElement;  

            if(parentsHasClass(target, 'more-snippets')) return;
            if(hasClass(target, 'more-snippets')) return;
            
            var more = document.querySelector('.more-snippets');
            var moreCategories = document.querySelector('.is-more-categories');
            
            more.className = more.className.replace('active', '');
            moreCategories.className = moreCategories.className.replace('active', '');
        });

        parent.document.addEventListener('click', function(e){
            var more = document.querySelector('.more-snippets');
            var moreCategories = document.querySelector('.is-more-categories');
            
            more.className = more.className.replace('active', '');
            moreCategories.className = moreCategories.className.replace('active', '');
        });

        function addSnippet(snippetid) {
            
            // TODO: var framework = parent._cb.opts.framework;
            var snippetPathReplace = parent._cb.opts.snippetPathReplace;
            var emailMode = parent._cb.opts.emailMode;

            // 
            for (let i = 0; i <snippets.length; i++) {
                if(snippets[i].id + ''=== snippetid) {
                    
                    var html = snippets[i].html;
                    var noedit = snippets[i].noedit;
                    break;
                }
            }

            var bSnippet;
            if (html.indexOf('row clearfix') === -1) {
                bSnippet = true; // Just snippet (without row/column grid)
            } else {
                bSnippet = false; // Snippet is wrapped in row/colum
            }
            if (emailMode) bSnippet = false;
 
            // Convert snippet into your defined 12 columns grid   
            var rowClass = parent._cb.opts.row; //row
            var colClass = parent._cb.opts.cols; //['col s1', 'col s2', 'col s3', 'col s4', 'col s5', 'col s6', 'col s7', 'col s8', 'col s9', 'col s10', 'col s11', 'col s12']
            if(rowClass!=='' && colClass.length===12){
                html = html.replace(new RegExp('row clearfix', 'g'), rowClass);
                html = html.replace(new RegExp('column full', 'g'), colClass[11]);
                html = html.replace(new RegExp('column half', 'g'), colClass[5]);
                html = html.replace(new RegExp('column third', 'g'), colClass[3]);
                html = html.replace(new RegExp('column fourth', 'g'), colClass[2]);
                html = html.replace(new RegExp('column fifth', 'g'), colClass[1]);
                html = html.replace(new RegExp('column sixth', 'g'), colClass[1]);
                html = html.replace(new RegExp('column two-third', 'g'), colClass[7]);
                html = html.replace(new RegExp('column two-fourth', 'g'), colClass[8]);
                html = html.replace(new RegExp('column two-fifth', 'g'), colClass[9]);
                html = html.replace(new RegExp('column two-sixth', 'g'), colClass[9]);
            }
            
            html = html.replace(/{id}/g, makeid()); // Replace {id} with auto generated id (for custom code snippet)

            if(parent._cb.opts.onAdd){
                html = parent._cb.opts.onAdd(html);
            }
            
            if(snippetPathReplace.length>0) {
                // try {
                    if (snippetPathReplace[0] != '') {
                        var regex = new RegExp(snippetPathReplace[0], 'g');
                        html = html.replace(regex, snippetPathReplace[1]);

                        var string1 = snippetPathReplace[0].replace(/\//g, '%2F');
                        var string2 = snippetPathReplace[1].replace(/\//g, '%2F');

                        var regex2 = new RegExp(string1, 'g');
                        html = html.replace(regex2, string2);
                    }
                // } catch (e) { }
            }
            
            parent._cb.addSnippet(html, bSnippet, noedit);

            var modal = parent.document.querySelector('.is-modal.snippets');
            removeClass(modal, 'active');

        }

        function hasClass(element, classname) {
            if(!element) return false;
            return element.classList ? element.classList.contains(classname) : new RegExp('\\b'+ classname+'\\b').test(element.className);
        }

        function removeClass(element, classname) {
            if(!element) return;
            if(element.classList.length>0) {
                element.className = element.className.replace(classname, '');
            }
        }
        
        function parentsHasClass(element, classname) {
            while (element) {
                if(element.tagName === 'BODY' || element.tagName === 'HTML') return false;
                if(!element.classList) return false;
                if (element.classList.contains(classname)) {
                    return true;
                }
                element = element.parentNode;
            }
        }

        function makeid() {//http://stackoverflow.com/questions/1349404/generate-a-string-of-5-random-characters-in-javascript
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            for (var i = 0; i < 2; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            var text2 = "";
            var possible2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < 5; i++)
                text2 += possible2.charAt(Math.floor(Math.random() * possible2.length));

            return text + text2;
        }

        /*! 
        pace 1.0.0 
        Copyright (c) 2013 HubSpot, Inc.
        MIT License
        */
        // (function () { var a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X = [].slice, Y = {}.hasOwnProperty, Z = function (a, b) { function c() { this.constructor = a } for (var d in b) Y.call(b, d) && (a[d] = b[d]); return c.prototype = b.prototype, a.prototype = new c, a.__super__ = b.prototype, a }, $ = [].indexOf || function (a) { for (var b = 0, c = this.length; c > b; b++) if (b in this && this[b] === a) return b; return -1 }; for (u = { catchupTime: 100, initialRate: .03, minTime: 250, ghostTime: 100, maxProgressPerFrame: 20, easeFactor: 1.25, startOnPageLoad: !0, restartOnPushState: !0, restartOnRequestAfter: 500, target: "body", elements: { checkInterval: 100, selectors: ["body"] }, eventLag: { minSamples: 10, sampleCount: 3, lagThreshold: 3 }, ajax: { trackMethods: ["GET"], trackWebSockets: !0, ignoreURLs: [] } }, C = function () { var a; return null != (a = "undefined" != typeof performance && null !== performance && "function" == typeof performance.now ? performance.now() : void 0) ? a : +new Date }, E = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame, t = window.cancelAnimationFrame || window.mozCancelAnimationFrame, null == E && (E = function (a) { return setTimeout(a, 50) }, t = function (a) { return clearTimeout(a) }), G = function (a) { var b, c; return b = C(), (c = function () { var d; return d = C() - b, d >= 33 ? (b = C(), a(d, function () { return E(c) })) : setTimeout(c, 33 - d) })() }, F = function () { var a, b, c; return c = arguments[0], b = arguments[1], a = 3 <= arguments.length ? X.call(arguments, 2) : [], "function" == typeof c[b] ? c[b].apply(c, a) : c[b] }, v = function () { var a, b, c, d, e, f, g; for (b = arguments[0], d = 2 <= arguments.length ? X.call(arguments, 1) : [], f = 0, g = d.length; g > f; f++) if (c = d[f]) for (a in c) Y.call(c, a) && (e = c[a], null != b[a] && "object" == typeof b[a] && null != e && "object" == typeof e ? v(b[a], e) : b[a] = e); return b }, q = function (a) { var b, c, d, e, f; for (c = b = 0, e = 0, f = a.length; f > e; e++) d = a[e], c += Math.abs(d), b++; return c / b }, x = function (a, b) { var c, d, e; if (null == a && (a = "options"), null == b && (b = !0), e = document.querySelector("[data-pace-" + a + "]")) { if (c = e.getAttribute("data-pace-" + a), !b) return c; try { return JSON.parse(c) } catch (f) { return d = f, "undefined" != typeof console && null !== console ? console.error("Error parsing inline pace options", d) : void 0 } } }, g = function () { function a() { } return a.prototype.on = function (a, b, c, d) { var e; return null == d && (d = !1), null == this.bindings && (this.bindings = {}), null == (e = this.bindings)[a] && (e[a] = []), this.bindings[a].push({ handler: b, ctx: c, once: d }) }, a.prototype.once = function (a, b, c) { return this.on(a, b, c, !0) }, a.prototype.off = function (a, b) { var c, d, e; if (null != (null != (d = this.bindings) ? d[a] : void 0)) { if (null == b) return delete this.bindings[a]; for (c = 0, e = []; c < this.bindings[a].length;) e.push(this.bindings[a][c].handler === b ? this.bindings[a].splice(c, 1) : c++); return e } }, a.prototype.trigger = function () { var a, b, c, d, e, f, g, h, i; if (c = arguments[0], a = 2 <= arguments.length ? X.call(arguments, 1) : [], null != (g = this.bindings) ? g[c] : void 0) { for (e = 0, i = []; e < this.bindings[c].length;) h = this.bindings[c][e], d = h.handler, b = h.ctx, f = h.once, d.apply(null != b ? b : this, a), i.push(f ? this.bindings[c].splice(e, 1) : e++); return i } }, a }(), j = window.Pace || {}, window.Pace = j, v(j, g.prototype), D = j.options = v({}, u, window.paceOptions, x()), U = ["ajax", "document", "eventLag", "elements"], Q = 0, S = U.length; S > Q; Q++) K = U[Q], D[K] === !0 && (D[K] = u[K]); i = function (a) { function b() { return V = b.__super__.constructor.apply(this, arguments) } return Z(b, a), b }(Error), b = function () { function a() { this.progress = 0 } return a.prototype.getElement = function () { var a; if (null == this.el) { if (a = document.querySelector(D.target), !a) throw new i; this.el = document.createElement("div"), this.el.className = "pace pace-active", document.body.className = document.body.className.replace(/pace-done/g, ""), document.body.className += " pace-running", this.el.innerHTML = '<div class="pace-progress">\n  <div class="pace-progress-inner"></div>\n</div>\n<div class="pace-activity"></div>', null != a.firstChild ? a.insertBefore(this.el, a.firstChild) : a.appendChild(this.el) } return this.el }, a.prototype.finish = function () { var a; return a = this.getElement(), a.className = a.className.replace("pace-active", ""), a.className += " pace-inactive", document.body.className = document.body.className.replace("pace-running", ""), document.body.className += " pace-done" }, a.prototype.update = function (a) { return this.progress = a, this.render() }, a.prototype.destroy = function () { try { this.getElement().parentNode.removeChild(this.getElement()) } catch (a) { i = a } return this.el = void 0 }, a.prototype.render = function () { var a, b, c, d, e, f, g; if (null == document.querySelector(D.target)) return !1; for (a = this.getElement(), d = "translate3d(" + this.progress + "%, 0, 0)", g = ["webkitTransform", "msTransform", "transform"], e = 0, f = g.length; f > e; e++) b = g[e], a.children[0].style[b] = d; return (!this.lastRenderedProgress || this.lastRenderedProgress | 0 !== this.progress | 0) && (a.children[0].setAttribute("data-progress-text", "" + (0 | this.progress) + "%"), this.progress >= 100 ? c = "99" : (c = this.progress < 10 ? "0" : "", c += 0 | this.progress), a.children[0].setAttribute("data-progress", "" + c)), this.lastRenderedProgress = this.progress }, a.prototype.done = function () { return this.progress >= 100 }, a }(), h = function () { function a() { this.bindings = {} } return a.prototype.trigger = function (a, b) { var c, d, e, f, g; if (null != this.bindings[a]) { for (f = this.bindings[a], g = [], d = 0, e = f.length; e > d; d++) c = f[d], g.push(c.call(this, b)); return g } }, a.prototype.on = function (a, b) { var c; return null == (c = this.bindings)[a] && (c[a] = []), this.bindings[a].push(b) }, a }(), P = window.XMLHttpRequest, O = window.XDomainRequest, N = window.WebSocket, w = function (a, b) { var c, d, e, f; f = []; for (d in b.prototype) try { e = b.prototype[d], f.push(null == a[d] && "function" != typeof e ? a[d] = e : void 0) } catch (g) { c = g } return f }, A = [], j.ignore = function () { var a, b, c; return b = arguments[0], a = 2 <= arguments.length ? X.call(arguments, 1) : [], A.unshift("ignore"), c = b.apply(null, a), A.shift(), c }, j.track = function () { var a, b, c; return b = arguments[0], a = 2 <= arguments.length ? X.call(arguments, 1) : [], A.unshift("track"), c = b.apply(null, a), A.shift(), c }, J = function (a) { var b; if (null == a && (a = "GET"), "track" === A[0]) return "force"; if (!A.length && D.ajax) { if ("socket" === a && D.ajax.trackWebSockets) return !0; if (b = a.toUpperCase(), $.call(D.ajax.trackMethods, b) >= 0) return !0 } return !1 }, k = function (a) { function b() { var a, c = this; b.__super__.constructor.apply(this, arguments), a = function (a) { var b; return b = a.open, a.open = function (d, e) { return J(d) && c.trigger("request", { type: d, url: e, request: a }), b.apply(a, arguments) } }, window.XMLHttpRequest = function (b) { var c; return c = new P(b), a(c), c }; try { w(window.XMLHttpRequest, P) } catch (d) { } if (null != O) { window.XDomainRequest = function () { var b; return b = new O, a(b), b }; try { w(window.XDomainRequest, O) } catch (d) { } } if (null != N && D.ajax.trackWebSockets) { window.WebSocket = function (a, b) { var d; return d = null != b ? new N(a, b) : new N(a), J("socket") && c.trigger("request", { type: "socket", url: a, protocols: b, request: d }), d }; try { w(window.WebSocket, N) } catch (d) { } } } return Z(b, a), b }(h), R = null, y = function () { return null == R && (R = new k), R }, I = function (a) { var b, c, d, e; for (e = D.ajax.ignoreURLs, c = 0, d = e.length; d > c; c++) if (b = e[c], "string" == typeof b) { if (-1 !== a.indexOf(b)) return !0 } else if (b.test(a)) return !0; return !1 }, y().on("request", function (b) { var c, d, e, f, g; return f = b.type, e = b.request, g = b.url, I(g) ? void 0 : j.running || D.restartOnRequestAfter === !1 && "force" !== J(f) ? void 0 : (d = arguments, c = D.restartOnRequestAfter || 0, "boolean" == typeof c && (c = 0), setTimeout(function () { var b, c, g, h, i, k; if (b = "socket" === f ? e.readyState < 2 : 0 < (h = e.readyState) && 4 > h) { for (j.restart(), i = j.sources, k = [], c = 0, g = i.length; g > c; c++) { if (K = i[c], K instanceof a) { K.watch.apply(K, d); break } k.push(void 0) } return k } }, c)) }), a = function () { function a() { var a = this; this.elements = [], y().on("request", function () { return a.watch.apply(a, arguments) }) } return a.prototype.watch = function (a) { var b, c, d, e; return d = a.type, b = a.request, e = a.url, I(e) ? void 0 : (c = "socket" === d ? new n(b) : new o(b), this.elements.push(c)) }, a }(), o = function () { function a(a) { var b, c, d, e, f, g, h = this; if (this.progress = 0, null != window.ProgressEvent) for (c = null, a.addEventListener("progress", function (a) { return h.progress = a.lengthComputable ? 100 * a.loaded / a.total : h.progress + (100 - h.progress) / 2 }, !1), g = ["load", "abort", "timeout", "error"], d = 0, e = g.length; e > d; d++) b = g[d], a.addEventListener(b, function () { return h.progress = 100 }, !1); else f = a.onreadystatechange, a.onreadystatechange = function () { var b; return 0 === (b = a.readyState) || 4 === b ? h.progress = 100 : 3 === a.readyState && (h.progress = 50), "function" == typeof f ? f.apply(null, arguments) : void 0 } } return a }(), n = function () { function a(a) { var b, c, d, e, f = this; for (this.progress = 0, e = ["error", "open"], c = 0, d = e.length; d > c; c++) b = e[c], a.addEventListener(b, function () { return f.progress = 100 }, !1) } return a }(), d = function () { function a(a) { var b, c, d, f; for (null == a && (a = {}), this.elements = [], null == a.selectors && (a.selectors = []), f = a.selectors, c = 0, d = f.length; d > c; c++) b = f[c], this.elements.push(new e(b)) } return a }(), e = function () { function a(a) { this.selector = a, this.progress = 0, this.check() } return a.prototype.check = function () { var a = this; return document.querySelector(this.selector) ? this.done() : setTimeout(function () { return a.check() }, D.elements.checkInterval) }, a.prototype.done = function () { return this.progress = 100 }, a }(), c = function () { function a() { var a, b, c = this; this.progress = null != (b = this.states[document.readyState]) ? b : 100, a = document.onreadystatechange, document.onreadystatechange = function () { return null != c.states[document.readyState] && (c.progress = c.states[document.readyState]), "function" == typeof a ? a.apply(null, arguments) : void 0 } } return a.prototype.states = { loading: 0, interactive: 50, complete: 100 }, a }(), f = function () { function a() { var a, b, c, d, e, f = this; this.progress = 0, a = 0, e = [], d = 0, c = C(), b = setInterval(function () { var g; return g = C() - c - 50, c = C(), e.push(g), e.length > D.eventLag.sampleCount && e.shift(), a = q(e), ++d >= D.eventLag.minSamples && a < D.eventLag.lagThreshold ? (f.progress = 100, clearInterval(b)) : f.progress = 100 * (3 / (a + 3)) }, 50) } return a }(), m = function () { function a(a) { this.source = a, this.last = this.sinceLastUpdate = 0, this.rate = D.initialRate, this.catchup = 0, this.progress = this.lastProgress = 0, null != this.source && (this.progress = F(this.source, "progress")) } return a.prototype.tick = function (a, b) { var c; return null == b && (b = F(this.source, "progress")), b >= 100 && (this.done = !0), b === this.last ? this.sinceLastUpdate += a : (this.sinceLastUpdate && (this.rate = (b - this.last) / this.sinceLastUpdate), this.catchup = (b - this.progress) / D.catchupTime, this.sinceLastUpdate = 0, this.last = b), b > this.progress && (this.progress += this.catchup * a), c = 1 - Math.pow(this.progress / 100, D.easeFactor), this.progress += c * this.rate * a, this.progress = Math.min(this.lastProgress + D.maxProgressPerFrame, this.progress), this.progress = Math.max(0, this.progress), this.progress = Math.min(100, this.progress), this.lastProgress = this.progress, this.progress }, a }(), L = null, H = null, r = null, M = null, p = null, s = null, j.running = !1, z = function () { return D.restartOnPushState ? j.restart() : void 0 }, null != window.history.pushState && (T = window.history.pushState, window.history.pushState = function () { return z(), T.apply(window.history, arguments) }), null != window.history.replaceState && (W = window.history.replaceState, window.history.replaceState = function () { return z(), W.apply(window.history, arguments) }), l = { ajax: a, elements: d, document: c, eventLag: f }, (B = function () { var a, c, d, e, f, g, h, i; for (j.sources = L = [], g = ["ajax", "elements", "document", "eventLag"], c = 0, e = g.length; e > c; c++) a = g[c], D[a] !== !1 && L.push(new l[a](D[a])); for (i = null != (h = D.extraSources) ? h : [], d = 0, f = i.length; f > d; d++) K = i[d], L.push(new K(D)); return j.bar = r = new b, H = [], M = new m })(), j.stop = function () { return j.trigger("stop"), j.running = !1, r.destroy(), s = !0, null != p && ("function" == typeof t && t(p), p = null), B() }, j.restart = function () { return j.trigger("restart"), j.stop(), j.start() }, j.go = function () { var a; return j.running = !0, r.render(), a = C(), s = !1, p = G(function (b, c) { var d, e, f, g, h, i, k, l, n, o, p, q, t, u, v, w; for (l = 100 - r.progress, e = p = 0, f = !0, i = q = 0, u = L.length; u > q; i = ++q) for (K = L[i], o = null != H[i] ? H[i] : H[i] = [], h = null != (w = K.elements) ? w : [K], k = t = 0, v = h.length; v > t; k = ++t) g = h[k], n = null != o[k] ? o[k] : o[k] = new m(g), f &= n.done, n.done || (e++ , p += n.tick(b)); return d = p / e, r.update(M.tick(b, d)), r.done() || f || s ? (r.update(100), j.trigger("done"), setTimeout(function () { return r.finish(), j.running = !1, j.trigger("hide") }, Math.max(D.ghostTime, Math.max(D.minTime - (C() - a), 0)))) : c() }) }, j.start = function (a) { v(D, a), j.running = !0; try { r.render() } catch (b) { i = b } return document.querySelector(".pace") ? (j.trigger("start"), j.go()) : setTimeout(j.start, 50) }, "function" == typeof define && define.amd ? define(function () { return j }) : "object" == typeof exports ? module.exports = j : D.startOnPageLoad && j.start() }).call(this);
        // Pace.on("hide", function () {
        //     $('.pace-inactive').remove();
        //     $(".is-category-list").css('visibility', 'visible');
        //     $(".is-design-list").css('visibility', 'visible');
        //     $(".is-category-list").fadeIn();
        //     $(".is-design-list").fadeIn();
        // });
    </script>

</textarea>
</body>

</html>