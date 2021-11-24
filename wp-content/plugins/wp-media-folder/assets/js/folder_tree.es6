/**
 * Folder tree for WP Media Folder
 */
let wpmfFoldersTreeModule;
(function ($) {
    wpmfFoldersTreeModule = {
        categories: [], // categories
        folders_states: [], // Contains open or closed status of folders
        cloudInterval: false,

        /**
         * Retrieve the Jquery tree view element
         * of the current frame
         * @return jQuery
         */
        getTreeElement: function () {
            if (wpmfFoldersModule.page_type === 'upload-grid' || wpmfFoldersModule.page_type === 'upload-list') {
                return $('.upload-php .wpmf-main-tree');
            } else {
                return wpmfFoldersModule.getFrame().find('.wpmf-main-tree').first();
            }
        },

        /**
         * Initialize module related things
         */
        initModule: function ($current_frame) {
            // Check if this frame has already the tree view
            let is_initialized;
            if (wpmfFoldersModule.page_type === 'upload-list' || wpmfFoldersModule.page_type === 'upload-grid') {
                is_initialized = $('.upload-php .wpmf-main-tree').length > 0;
            } else {
                // not show tree on 900px of screen
                if ($(window).width() <= 900) {
                    return;
                }

                if ($current_frame.hasClass('hide-menu')) {
                    // add placeholder for search media input
                    $current_frame.find('#media-search-input').attr('placeholder', $current_frame.find('.media-search-input-label').text());
                    if ($(window).width() > 768) {
                        $current_frame.addClass("wpmf-treeview").removeClass("hide-menu");
                    }
                }

                is_initialized = $current_frame.find('.media-frame-menu .wpmf-main-tree').length > 0;
            }

            if (is_initialized) {
                // Show folder tree in case it has been hidden previously
                wpmfFoldersTreeModule.getTreeElement().show();
                return;
            }

            // Import categories from wpmf main module
            wpmfFoldersTreeModule.importCategories();

            if (wpmfFoldersModule.page_type === 'upload-list' || wpmfFoldersModule.page_type === 'upload-grid') {
                if (!$('.wpmf-main-tree').length) {
                    $('<div class="wpmf-main-tree"></div>').insertBefore($('#wpbody-content'));
                }
            } else {
                let $menu = $current_frame.find('.media-frame-menu .media-menu');
                if (!$menu.length) {
                    $menu = $current_frame.find('.media-frame-menu');
                    $current_frame.find('.media-frame-menu-heading').hide();
                }
                if (!$menu.find('.wpmf-main-tree').length) {
                    $('<div class="wpmf-main-tree"></div>').appendTo($menu);
                }
            }

            // Render the tree view
            wpmfFoldersTreeModule.loadTreeView();
            // Subscribe to the change folder event in main wpmf module
            wpmfFoldersModule.on('changeFolder', function (folder_id) {
                wpmfFoldersTreeModule.changeFolder(folder_id);
            });

            // Subscribe to the add folder event in main wpmf module
            wpmfFoldersModule.on(['addFolder', 'deleteFolder', 'updateFolder', 'moveFolder', 'foldersSelection'], function (folder) {
                wpmfFoldersTreeModule.importCategories();
                wpmfFoldersTreeModule.loadTreeView();
                // Initialize folder tree resizing
                wpmfFoldersTreeModule.initContainerResizing($current_frame);
            });

            // Subscribe to the move file event in main wpmf module
            wpmfFoldersModule.on('moveFile', function (files_ids, folder_to_id, folder_from_id) {
                // Update file count in main wpmf Module
                wpmfFoldersModule.categories[folder_from_id].files_count -= files_ids.length;

                wpmfFoldersModule.categories[folder_to_id].files_count += files_ids.length;

                // Import categories with updated count
                wpmfFoldersTreeModule.importCategories();

                // Reload tree view
                wpmfFoldersTreeModule.loadTreeView();
                wpmfFoldersTreeModule.initContainerResizing($current_frame);
            });

            wpmfFoldersModule.on('deleteFile', function (folder_id) {
                // Update file count in main wpmf Module
                wpmfFoldersModule.categories[folder_id].files_count -= 1;
                // Import categories with updated count
                wpmfFoldersTreeModule.importCategories();
                // Reload tree view
                wpmfFoldersTreeModule.loadTreeView();
                wpmfFoldersTreeModule.initContainerResizing($current_frame);
            });

            // Initialize the fixed tree view position on scrolling
            if (wpmf.vars.wpmf_pagenow === 'upload.php') {
                wpmfFoldersTreeModule.initFixedScrolling($current_frame);
            }

            // Subscribe to ordering folder filter
            wpmfFoldersFiltersModule.on('foldersOrderChanged', function () {
                wpmfFoldersTreeModule.importCategories();
                wpmfFoldersTreeModule.loadTreeView();
                wpmfFoldersTreeModule.initContainerResizing($current_frame);
            });

            wpmfFoldersModule.on('foldersOrderChanged', function () {
                wpmfFoldersTreeModule.importCategories();
                wpmfFoldersTreeModule.loadTreeView();
                wpmfFoldersTreeModule.initContainerResizing($current_frame);
            });

            wpmfFoldersModule.on('foldersCountChanged', function () {
                wpmfFoldersTreeModule.loadTreeView();
                wpmfFoldersTreeModule.initContainerResizing($current_frame);
            });

            // Subscribe to gallery editing to hide folder tree
            wpmfFoldersModule.on('wpGalleryEdition', function () {
                wpmfFoldersTreeModule.getTreeElement().hide();
            });
            wpmfFoldersTreeModule.initContainerResizing($current_frame);
        },

        /**
         * Import categories from wpmf main module
         */
        importCategories: function () {
            let folders_ordered = [];

            // Add each category
            $(wpmfFoldersModule.categories_order).each(function () {
                folders_ordered.push(wpmfFoldersModule.categories[this]);
            });

            let folder_order = wpmfFoldersModule.getCookie('#media-order-folder' + wpmf.vars.site_url);
            if (typeof folder_order !== "undefined") {
                wpmfFoldersModule.folder_ordering = folder_order;
            }

            // Order the array depending on main ordering
            switch (wpmfFoldersModule.folder_ordering) {
                default:
                case 'name-ASC':
                    folders_ordered = Object.values(folders_ordered).sort(function (a, b) {
                        if (a.id === 0) return -1; // Root folder is always first
                        if (b.id === 0) return 1; // Root folder is always first
                        return a.label.localeCompare(b.label);
                    });
                    break;
                case 'name-DESC':
                    folders_ordered = Object.values(folders_ordered).sort(function (a, b) {
                        if (a.id === 0) return -1; // Root folder is always first
                        if (b.id === 0) return 1; // Root folder is always first
                        return b.label.localeCompare(a.label);
                    });
                    break;
                case 'id-ASC':
                    folders_ordered = Object.values(folders_ordered).sort(function (a, b) {
                        if (a.id === 0) return -1; // Root folder is always first
                        if (b.id === 0) return 1; // Root folder is always first
                        return a.id - b.id;
                    });
                    break;
                case 'id-DESC':
                    folders_ordered = Object.values(folders_ordered).sort(function (a, b) {
                        if (a.id === 0) return -1; // Root folder is always first
                        if (b.id === 0) return 1; // Root folder is always first
                        return b.id - a.id;
                    });
                    break;
                case 'custom':
                    folders_ordered = Object.values(folders_ordered).sort(function (a, b) {
                        if (a.id === 0) return -1; // Root folder is always first
                        if (b.id === 0) return 1; // Root folder is always first
                        return a.order - b.order;
                    });
                    break;
            }

            // Reorder array based on children
            let folders_ordered_deep = [];
            let processed_ids = [];
            const loadChildren = function (id) {
                if (processed_ids.indexOf(id) < 0) {
                    processed_ids.push(id);
                    for (let ij = 0; ij < folders_ordered.length; ij++) {
                        if (folders_ordered[ij].parent_id === id) {
                            folders_ordered_deep.push(folders_ordered[ij]);
                            loadChildren(folders_ordered[ij].id);
                        }
                    }
                }
            };
            loadChildren(parseInt(wpmf.vars.term_root_id));

            // Finally save it to the global var
            wpmfFoldersTreeModule.categories = folders_ordered_deep;

        },

        /**
         * Render tree view inside content
         */
        loadTreeView: function () {
            // update height for folder tree when toggle menu
            if (wpmf.vars.wpmf_pagenow !== 'upload.php') {
                let $current_frame = wpmfFoldersTreeModule.getTreeElement().closest('.media-frame');
                const $menu = $current_frame.find('.media-frame-menu');
                if (!$current_frame.find('.wpmf-toggle-media-menu').length) {
                    $current_frame.addClass('wpmf_hide_media_menu');
                    $current_frame.find('.media-frame-menu-heading').append('<span class="material-icons wpmf-toggle-media-menu wpmf-toggle-down"> arrow_drop_down </span><span class="material-icons wpmf-toggle-media-menu wpmf-toggle-up"> arrow_drop_up </span>');
                    $current_frame.find('.wpmf-toggle-media-menu').off('click').bind('click', function () {
                        if ($current_frame.hasClass('wpmf_hide_media_menu')) {
                            $current_frame.removeClass('wpmf_hide_media_menu').addClass('wpmf_show_media_menu');
                            var a = $menu.find(".media-menu-item").length;
                            $menu.find('.wpmf-all-tree').height($menu.height() - 34 * a - 220);
                        } else {
                            $current_frame.removeClass('wpmf_show_media_menu').addClass('wpmf_hide_media_menu');
                            $menu.find('.wpmf-all-tree').height($menu.height() - 220);
                        }
                    });
                }
            }

            // render folder tree
            wpmfFoldersTreeModule.getTreeElement().html(wpmfFoldersTreeModule.getRendering());
            // load count by subfolders
            if (wpmfFoldersModule.show_files_count) {
                if (wpmfFoldersTreeModule.categories.length < 1000) {
                    wpmfFoldersTreeModule.loadCountAll();
                } else {
                    wpmfFoldersTreeModule.getTreeElement().addClass('wpmf-many-folders');
                }
            }
            wpmfFoldersModule.openContextMenuFolder();
            let append_element;

            if (wpmfFoldersModule.page_type === 'upload-list') {
                append_element = '#posts-filter';
            } else {
                append_element = '.media-frame';
            }

            let folder_order = wpmfFoldersModule.getCookie('#media-order-folder' + wpmf.vars.site_url);
            let sortable = false;
            if (typeof folder_order !== "undefined" && folder_order === 'custom') {
                sortable = true;
            }

            if (sortable) {
                if ($().sortable) {
                    wpmfFoldersTreeModule.getTreeElement().find('ul').sortable({
                        placeholder: 'wpmf_tree_drop_sort',
                        delay: 100, // Prevent dragging when only trying to click
                        distance: 10,
                        cursorAt: {top: 10, left: 10},
                        revert: true,
                        revertDuration: 1000,
                        /*tolerance: "intersect",*/
                        helper: function (ui) {
                            var helper = '<div class="wpmf-move-element">';
                            helper += '<span class="mdc-list-item__start-detail"><i class="material-icons wpmf-icon-category">folder</i></span>';
                            helper += '<span class="mdc-list-item__text"> '+ wpmf.l18n.folder_moving_text +' </span>';
                            helper += '</div>';
                            return helper;
                        },
                        /** Prevent firefox bug positionnement **/
                        start: function (event, ui) {
                            wpmfFoldersTreeModule.getTreeElement().addClass('wpmf_tree_sorting');
                            var userAgent = navigator.userAgent.toLowerCase();
                            if (ui.helper !== "undefined" && userAgent.match(/firefox/)) {
                                ui.helper.css('position', 'absolute');
                            }
                        },
                        stop: function (event, ui) {
                            wpmfFoldersTreeModule.getTreeElement().removeClass('wpmf_tree_sorting');
                        },
                        beforeStop: function (event, ui) {

                        },
                        update: function (event, ui) {
                            var order = '';
                            $(event.target).find('li').each(function (i, val) {
                                var id = $(val).data('id');
                                if (id !== 0) {
                                    if (order !== '') {
                                        order += ',';
                                    }
                                    order += '"' + i + '":' + id;
                                    wpmfFoldersModule.categories[id].order = i;
                                }
                            });
                            order = '{' + order + '}';

                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: {
                                    action: "wpmf",
                                    task: "reorderfolder",
                                    order: order,
                                    wpmf_nonce: wpmf.vars.wpmf_nonce
                                },
                                success: function (res) {
                                    wpmfFoldersModule.renderFolders();
                                }
                            });
                        }
                    }).disableSelection();
                }
            } else {
                if ($().draggable) {
                    // Initialize dragping folder on tree view
                    wpmfFoldersTreeModule.getTreeElement().find('ul li .wpmf-item[data-id!="0"]').draggable({
                        revert: true,
                        revertDuration: 1000,
                        helper: function (ui) {
                            var helper = '<div class="wpmf-move-element">';
                            helper += '<span class="mdc-list-item__start-detail" role="presentation"><i class="material-icons wpmf-icon-category">folder</i></span>';
                            helper += '<span class="mdc-list-item__text"> '+ wpmf.l18n.folder_moving_text +' </span>';
                            helper += '</div>';
                            return helper;
                        },
                        appendTo: append_element,
                        delay: 100, // Prevent dragging when only trying to click
                        distance: 10,
                        cursorAt: {top: 0, left: 0},
                        drag: function () {
                        },
                        start: function (event, ui) {
                            // Add the original size of element
                            $(ui.helper).css('width', $(ui.helper.context).outerWidth() + 'px');
                            $(ui.helper).css('height', $(ui.helper.context).outerWidth() + 'px');

                            // Add some style to original elements
                            $(this).addClass('wpmf-dragging');
                        },
                        stop: function (event, ui) {
                            // Revert style
                            $(this).removeClass('wpmf-dragging');
                        }
                    });
                }
            }

            if ($().droppable) {
                // Initialize dropping folder on tree view
                wpmfFoldersTreeModule.getTreeElement().find('ul li .wpmf-item-inside').droppable({
                    hoverClass: "wpmf-hover-folder",
                    tolerance: 'pointer',
                    over: function (event, ui) {
                        $('.wpmf_tree_drop_sort').hide();
                    },
                    out: function (event, ui) {
                        $('.wpmf_tree_drop_sort').show();
                    },
                    drop: function (event, ui) {
                        event.stopPropagation();
                        $(ui.helper).addClass('wpmf_dragout');
                        if ($(ui.draggable).hasClass('wpmf-folder') || $(ui.draggable).hasClass('wpmf-item')) {
                            // move folder with folder tree
                            wpmfFoldersModule.moveFolder($(ui.draggable).data('id'), $(this).data('id'));
                        } else {
                            // Transfer the event to the wpmf main module
                            wpmfFoldersModule.droppedAttachment($(this).data('id'));
                        }
                    }
                });
            }

            // Initialize change keyword to search folder
            wpmfFoldersTreeModule.getTreeElement().find('.searchfolder').on('click', function (e) {
                wpmfFoldersTreeModule.doSearch();
            });

            // search with enter key
            $('.wpmf_search_folder').on('keypress', function (e) {
                if (e.which === 13) {
                    wpmfFoldersTreeModule.doSearch();
                    return false;
                }
            });

            // Initialize double click to folder title on tree view
            wpmfFoldersTreeModule.getTreeElement().find('ul .wpmf-item[data-id]').wpmfSingleDoubleClick(function (e) {
                if ($(e.target).hasClass('wpmf-arrow')) {
                    return;
                }
                // single click
                let id = $(this).data('id');
                if (parseInt(id) !== parseInt(wpmfFoldersModule.last_selected_folder)) {
                    if (!wpmfFoldersModule.getFrame().find('#wpmf-media-category').length) {
                        let bread = '';
                        wpmfFoldersTreeModule.changeFolder(id);
                        if (parseInt(id) === 0) {
                            bread = wpmfFoldersModule.getBreadcrumb(0);
                        } else {
                            bread = wpmfFoldersModule.getBreadcrumb(id);
                        }
                        $('.wpmf_msg_upload_folder span').html(bread);
                    } else {
                        wpmfFoldersModule.changeFolder(id);
                    }
                }
            }, function (e) {
                // double click
                let id = $(this).data('id');
                wpmfFoldersModule.clickEditFolder(e, id);
                wpmfFoldersModule.houtside();
            });

            wpmfFoldersTreeModule.getTreeElement().append('<div class="wpmf-all-tree scrollbar-inner"></div>');
            wpmfFoldersTreeModule.getTreeElement().find('.wpmf_media_library').appendTo(wpmfFoldersTreeModule.getTreeElement().find('.wpmf-all-tree'));
            if (parseInt(wpmf.vars.wpmf_addon_active) === 1) {
                if (!wpmfFoldersTreeModule.getTreeElement().find('.wpmf-dropbox').length && wpmfFoldersTreeModule.getTreeElement().find('.dropbox_list').length) {
                    wpmfFoldersTreeModule.getTreeElement().find('.wpmf-all-tree').append('<ul class="wpmf-dropbox"></ul>');
                    wpmfFoldersTreeModule.getTreeElement().find('.dropbox_list').appendTo(wpmfFoldersTreeModule.getTreeElement().find('.wpmf-dropbox'));
                }

                if (!wpmfFoldersTreeModule.getTreeElement().find('.wpmf-google').length && wpmfFoldersTreeModule.getTreeElement().find('.google_drive_list').length) {
                    wpmfFoldersTreeModule.getTreeElement().find('.wpmf-all-tree').append('<ul class="wpmf-google"></ul>');
                    wpmfFoldersTreeModule.getTreeElement().find('.google_drive_list').appendTo(wpmfFoldersTreeModule.getTreeElement().find('.wpmf-google'));
                }

                if (!wpmfFoldersTreeModule.getTreeElement().find('.wpmf-onedrive').length && wpmfFoldersTreeModule.getTreeElement().find('.onedrive_list').length) {
                    wpmfFoldersTreeModule.getTreeElement().find('.wpmf-all-tree').append('<ul class="wpmf-onedrive"></ul>');
                    wpmfFoldersTreeModule.getTreeElement().find('.onedrive_list').appendTo(wpmfFoldersTreeModule.getTreeElement().find('.wpmf-onedrive'));
                }

                if (!wpmfFoldersTreeModule.getTreeElement().find('.wpmf-onedrive-business').length && wpmfFoldersTreeModule.getTreeElement().find('.onedrive_business_list').length) {
                    wpmfFoldersTreeModule.getTreeElement().find('.wpmf-all-tree').append('<ul class="wpmf-onedrive-business"></ul>');
                    wpmfFoldersTreeModule.getTreeElement().find('.onedrive_business_list').appendTo(wpmfFoldersTreeModule.getTreeElement().find('.wpmf-onedrive-business'));
                }
            }

            // load scroll bar
            if (!$('body').hasClass('rtl')) {
                $('.wpmf-all-tree.scrollbar-inner').scrollbar();
            }
        },

        loadCountAll: function () {
            wpmfFoldersTreeModule.getTreeElement().find('li').each(function (i, element) {
                let id = $(element).data('id');
                if (parseInt(id) !== 0) {
                    let countElements = $(element).find('.wpmf-item-count');
                    let count = 0;
                    $(countElements).each(function (index, countElement) {
                        count += parseInt($(countElement).html());
                    });

                    $(element).find('> .wpmf-item .wpmf-item-count-all').html(count);
                }
            });
        },

        /**
         *  Do search folder
         */
        doSearch: function () {
            // search on folder tree
            let keyword = $('.wpmf_search_folder').val().trim().toLowerCase();
            let search_folders = [];
            // get folder when disable folders on right bar
            let folder_search = [];
            for (let folder_id in wpmfFoldersModule.categories) {
                if (keyword !== '') {
                    keyword = keyword.trim().toLowerCase();
                    let folder_name = wpmfFoldersModule.categories[folder_id].lower_label;
                    if (folder_name.indexOf(keyword) !== -1) {
                        folder_search.push(folder_id);
                    }
                }
            }
            search_folders = folder_search;
            if (keyword !== '') {
                $('.wpmf-main-tree li').addClass('folderhide');
                $.each(search_folders, function (i, v) {
                    $('.wpmf-main-tree li[data-id="' + v + '"]').addClass('foldershow').removeClass('folderhide closed');
                    $('.wpmf-main-tree li[data-id="' + v + '"]').parents('.wpmf-main-tree li').addClass('foldershow').removeClass('folderhide closed');
                });
            } else {
                $('.wpmf-main-tree li').removeClass('foldershow folderhide');
            }
        },

        /**
         * Get the html resulting tree view
         * @return {string}
         */
        getRendering: function () {
            let ij = 0;
            let content = ''; // Final tree view cwpmf-main-tree-resizeontent
            // render search folder box
            let search_folder = `
            <div class="wpmf-expandable-search mdl-cell--hide-phone">
                <form action="#">
                  <input type="text" class="wpmf_search_folder" placeholder="${wpmf.l18n.search_folder}" size="1">
                </form>
                <i class="material-icons searchfolder">search</i>
            </div>
            `;

            // get last status folder tree
            let lastStatusTree = wpmfFoldersModule.getCookie('lastStatusTree_' + wpmf.vars.site_url);
            if (lastStatusTree !== '') {
                lastStatusTree = JSON.parse(lastStatusTree);
            }

            /**
             * Recursively print list of folders
             * @return {boolean}
             */
            const generateList = function (tree_class = '') {
                content += '<ul class="' + tree_class + '">';

                while (ij < wpmfFoldersTreeModule.categories.length) {
                    let className = '';

                    // get color folder
                    let bgcolor = '', odvColor = '';
                    if (typeof wpmf.vars.colors !== 'undefined' && typeof wpmf.vars.colors[wpmfFoldersTreeModule.categories[ij].id] !== 'undefined') {
                        bgcolor = 'color: ' + wpmf.vars.colors[wpmfFoldersTreeModule.categories[ij].id];
                        odvColor = wpmf.vars.colors[wpmfFoldersTreeModule.categories[ij].id];
                    } else {
                        bgcolor = 'color: #b2b2b2';
                        odvColor = '#b2b2b2';
                    }

                    let icondrive;
                    if (parseInt(wpmfFoldersTreeModule.categories[ij].id) === 0) {
                        icondrive = '<i class="wpmf-item-icon wpmf-item-icon-root"></i>';
                    } else {
                        icondrive = '<i class="material-icons wpmf-item-icon" style="' + bgcolor + '">folder</i>';
                    }


                    if (lastStatusTree.indexOf(wpmfFoldersTreeModule.categories[ij].id) !== -1 || parseInt(wpmfFoldersTreeModule.categories[ij].id) === 0) {
                        className += 'open ';
                    } else {
                        className += 'closed ';
                    }

                    let drive_root = false;

                    // get last access folder
                    let lastAccessFolder = wpmfFoldersModule.getCookie('lastAccessFolder_' + wpmf.vars.site_url);
                    // Select the last element which was selected in wpmf main module
                    if (typeof lastAccessFolder === "undefined" || (typeof lastAccessFolder !== "undefined" && lastAccessFolder === '') || (typeof lastAccessFolder !== "undefined" && parseInt(lastAccessFolder) === 0) || typeof wpmfFoldersModule.categories[lastAccessFolder] === "undefined") {
                        if (wpmfFoldersTreeModule.categories[ij].id === wpmfFoldersModule.last_selected_folder) {
                            className += 'selected ';
                        }
                    } else {
                        if (wpmfFoldersTreeModule.categories[ij].id === parseInt(lastAccessFolder)) {
                            className += 'selected ';
                        }
                    }

                    if (parseInt(wpmf.vars.wpmf_addon_active) === 1) {
                        if (wpmfFoldersTreeModule.categories[ij].drive_type === 'onedrive_business') {
                            if (parseInt(wpmfFoldersTreeModule.categories[ij].parent_id) === 0) {
                                drive_root = true;
                                className += 'onedrive_business_list wpmf_drive_tree';
                                className = className.replace('closed', '');
                            }
                        }

                        if (wpmfFoldersTreeModule.categories[ij].drive_type === 'onedrive') {
                            if (parseInt(wpmfFoldersTreeModule.categories[ij].parent_id) === 0) {
                                drive_root = true;
                                className += 'onedrive_list wpmf_drive_tree';
                                className = className.replace('closed', '');
                            }
                        }

                        if (wpmfFoldersTreeModule.categories[ij].drive_type === 'onedrive' || wpmfFoldersTreeModule.categories[ij].drive_type === 'onedrive_business') {
                            icondrive = `<svg class="tree_drive_icon_img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60.43 35.95"><defs></defs><title>icon</title><path class="cls-1" d="M39.45,36.6H55.53a5.41,5.41,0,0,0,5.15-2.77c1.75-3.14,1.41-8.69-3.72-10.35-.55-.18-.91-.27-.93-1-.13-6.16-6.1-9.95-12.23-7.73a1.21,1.21,0,0,1-1.65-.47,10,10,0,0,0-8.49-4c-5.29.2-8.84,3.31-10.08,8.57a1.9,1.9,0,0,1-1.84,1.73c-3.41.53-6.06,2.74-6.43,5.52-.77,5.7,1.55,10.47,8.49,10.51C29,36.62,34.23,36.6,39.45,36.6Z" transform="translate(-1.2 -0.66)" style="fill:#fefefe"/><path class="cls-1" d="M14.58,34c-.23-.54-.4-.93-.55-1.31-2.29-5.83-.42-11.5,6.08-13.45a2.7,2.7,0,0,0,2.06-2.13,12.4,12.4,0,0,1,11.89-8.7,11,11,0,0,1,8.49,3.83c.35.4.66,1,1.4.6a6.16,6.16,0,0,1,2.49-.57c.92-.12,1.08-.45.85-1.31-1.52-5.74-5.24-9.23-11-10.15C31.12,0,26.9,2,24,6.43a1.12,1.12,0,0,1-1.72.47,8.52,8.52,0,0,0-5.6-.59C11.73,7.41,8.76,11,8.49,16.37c0,.9-.22,1.14-1.1,1.36A7.92,7.92,0,0,0,1.22,25,8.39,8.39,0,0,0,5.6,33C8.43,34.53,11.46,33.83,14.58,34Z" transform="translate(-1.2 -0.66)" style="fill: #fefefe"/><path class="cls-2" d="M39.45,36.6c-5.22,0-10.43,0-15.65,0-6.94,0-9.26-4.81-8.49-10.51.37-2.78,3-5,6.43-5.52a1.9,1.9,0,0,0,1.84-1.73c1.24-5.26,4.79-8.37,10.08-8.57a10,10,0,0,1,8.49,4,1.21,1.21,0,0,0,1.65.47c6.13-2.22,12.1,1.57,12.23,7.73,0,.72.38.81.93,1,5.13,1.66,5.47,7.21,3.72,10.35a5.41,5.41,0,0,1-5.15,2.77Z" transform="translate(-1.2 -0.66)" style="fill: ${odvColor}"/><path class="cls-2" d="M14.58,34c-3.12-.2-6.15.5-9-1.07a8.39,8.39,0,0,1-4.38-8,7.92,7.92,0,0,1,6.17-7.25c.88-.22,1.06-.46,1.1-1.36.27-5.35,3.24-9,8.17-10.06a8.52,8.52,0,0,1,5.6.59A1.12,1.12,0,0,0,24,6.43C26.9,2,31.12,0,36.28.84c5.77.92,9.49,4.41,11,10.15.23.86.07,1.19-.85,1.31a6.16,6.16,0,0,0-2.49.57c-.74.44-1.05-.2-1.4-.6a11,11,0,0,0-8.49-3.83,12.4,12.4,0,0,0-11.89,8.7,2.7,2.7,0,0,1-2.06,2.13c-6.5,1.95-8.37,7.62-6.08,13.45C14.18,33.1,14.35,33.49,14.58,34Z" transform="translate(-1.2 -0.66)" style="fill: ${odvColor}"/></svg>`;
                        }

                        if (wpmfFoldersTreeModule.categories[ij].drive_type === 'dropbox') {
                            if (parseInt(wpmfFoldersTreeModule.categories[ij].parent_id) === 0) {
                                drive_root = true;
                                className += 'dropbox_list wpmf_drive_tree';
                                className = className.replace('closed', '');
                            }

                            icondrive = '<i class="zmdi zmdi-dropbox tree_drive_icon" style="' + bgcolor + '"></i>';
                        }

                        if (wpmfFoldersTreeModule.categories[ij].drive_type === 'google_drive') {
                            if (parseInt(wpmfFoldersTreeModule.categories[ij].parent_id) === 0) {
                                drive_root = true;
                                className += 'google_drive_list wpmf_drive_tree';
                                className = className.replace('closed', '');
                            }

                            icondrive = '<i class="zmdi zmdi-google-drive tree_drive_icon" style="' + bgcolor + '"></i>';
                        }

                        if (typeof wpmfFoldersTreeModule.categories[ij].drive_type === 'undefined' || wpmfFoldersTreeModule.categories[ij].drive_type === '') {
                            className += ' wpmf_local_tree ';
                        }
                    }

                    // Open li tag
                    content += '<li class="' + className + '" data-id="' + wpmfFoldersTreeModule.categories[ij].id + '">';
                    let pad = (wpmfFoldersTreeModule.categories[ij].depth) * 15;
                    if (wpmfFoldersTreeModule.categories[ij].drive_type !== '') {
                        pad = pad - 20;
                    }
                    content += '<div class="wpmf-item" data-id="' + wpmfFoldersTreeModule.categories[ij].id + '">';
                    content += '<div class="wpmf-item-inside" data-id="' + wpmfFoldersTreeModule.categories[ij].id + '" style="padding-left: '+ pad +'px">';
                    const a_tag = '<a class="wpmf-text-item" data-id="' + wpmfFoldersTreeModule.categories[ij].id + '">';

                    if (drive_root) {
                        content += a_tag;
                    } else {
                        if (wpmfFoldersTreeModule.categories[ij + 1] && wpmfFoldersTreeModule.categories[ij + 1].depth > wpmfFoldersTreeModule.categories[ij].depth) {
                            // The next element is a sub folder
                            content += '<a class="wpmf-toggle-icon" onclick="wpmfFoldersTreeModule.toggle(' + wpmfFoldersTreeModule.categories[ij].id + ')"><i class="tree_arrow_right_icon wpmf-arrow"></i></a>';
                            content += a_tag;
                        } else {
                            content += '<a class="wpmf-toggle-icon wpmf-notoggle-icon"><i class="tree_arrow_right_icon"></i></a>';
                            content += a_tag;
                        }
                    }

                    // Add folder icon
                    content += icondrive;

                    // Add current category name
                    if (wpmfFoldersTreeModule.categories[ij].id === 0) {
                        // If this is the root folder then rename it
                        content += '<span class="wpmf-item-title">' + wpmf.l18n.media_folder + '</span>';
                    } else {
                        content += '<span class="wpmf-item-title">' + wpmfFoldersTreeModule.categories[ij].label + '</span>';
                    }

                    content += '</a>';
                    if (wpmfFoldersModule.show_files_count && wpmfFoldersTreeModule.categories[ij].files_count !== undefined) {
                        if (wpmfFoldersTreeModule.categories[ij].id === 0) {
                            if (wpmf.vars.root_media_count) {
                                content += '<span class="wpmf-item-count">' + wpmfFoldersTreeModule.categories[ij].files_count + '</span>';
                            }
                        } else {
                            content += '<span class="wpmf-item-count">' + wpmfFoldersTreeModule.categories[ij].files_count + '</span>';
                        }
                    }

                    if (wpmfFoldersModule.show_files_count && wpmfFoldersTreeModule.categories[ij].count_all !== undefined) {
                        content += '<span class="wpmf-item-count-all">' + wpmfFoldersTreeModule.categories[ij].count_all + '</span>';
                    }
                    content += '</div></div>';
                    // This is the end of the array
                    if (wpmfFoldersTreeModule.categories[ij + 1] === undefined) {
                        // Let's close all opened tags
                        for (let ik = wpmfFoldersTreeModule.categories[ij].depth; ik >= 0; ik--) {
                            content += '</li>';
                            content += '</ul>';
                        }

                        // We are at the end don't continue to process array
                        return false;
                    }

                    if (wpmfFoldersTreeModule.categories[ij + 1].depth > wpmfFoldersTreeModule.categories[ij].depth) { // The next element is a sub folder
                        // Recursively list it
                        ij++;
                        if (generateList() === false) {
                            // We have reached the end, let's recursively end
                            return false;
                        }
                    } else if (wpmfFoldersTreeModule.categories[ij + 1].depth < wpmfFoldersTreeModule.categories[ij].depth) { // The next element don't have the same parent
                        // Let's close opened tags
                        for (let ik = wpmfFoldersTreeModule.categories[ij].depth; ik > wpmfFoldersTreeModule.categories[ij + 1].depth; ik--) {
                            content += '</li>';
                            content += '</ul>';
                        }

                        // We're not at the end of the array let's continue processing it
                        return true;
                    }

                    // Close the current element
                    content += '</li>';
                    ij++;
                }
            };

            // Start generation
            generateList('wpmf_media_library');

            // Add the new folder button
            content = '<a class="wpmf-new-folder" onclick="wpmfFoldersModule.newFolder(wpmfFoldersModule.last_selected_folder)"><i class="material-icons">add</i>' + wpmf.l18n.create_folder + '</a>' + search_folder + content;

            return content;
        },

        /**
         * Change the selected folder in tree view
         * @param folder_id
         */
        changeFolder: function (folder_id) {
            // Remove previous selection
            wpmfFoldersTreeModule.getTreeElement().find('li').removeClass('selected');

            // Select the folder
            wpmfFoldersTreeModule.getTreeElement().find('li[data-id="' + folder_id + '"]').addClass('selected').// Open parent folders
            parents('.wpmf-main-tree li.closed').removeClass('closed');
        },

        /**
         * Toggle the open / closed state of a folder
         * @param folder_id
         */
        toggle: function (folder_id) {
            // get last status folder tree
            let lastStatusTree = [];
            // Check is folder has closed class
            if (wpmfFoldersTreeModule.getTreeElement().find('li[data-id="' + folder_id + '"]').hasClass('closed')) {
                // Open the folder
                wpmfFoldersTreeModule.openFolder(folder_id);
            } else {
                // Close the folder
                wpmfFoldersTreeModule.closeFolder(folder_id);
                // close all sub folder
                $('li[data-id="' + folder_id + '"]').find('li').addClass('closed');
            }

            wpmfFoldersTreeModule.getTreeElement().find('li:not(.closed)').each(function (i, v) {
                let id = $(v).data('id');
                lastStatusTree.push(id);
            });
            // set last status folder tree
            wpmfFoldersModule.setCookie("lastStatusTree_" + wpmf.vars.site_url, JSON.stringify(lastStatusTree), 365);
        },


        /**
         * Open a folder to show children
         */
        openFolder: function (folder_id) {
            wpmfFoldersTreeModule.getTreeElement().find('li[data-id="' + folder_id + '"]').removeClass('closed');
            wpmfFoldersTreeModule.folders_states[folder_id] = 'open';
        },

        /**
         * Close a folder and hide children
         */
        closeFolder: function (folder_id) {
            wpmfFoldersTreeModule.getTreeElement().find('li[data-id="' + folder_id + '"]').addClass('closed');
            wpmfFoldersTreeModule.folders_states[folder_id] = 'close';
        },

        /**
         * Initialize the fixed position when user is scrolling
         * to keep the folder tree always visible
         */
        initFixedScrolling: function () {
            setTimeout(function () {
                // Fix initial left margin in list view
                if (wpmfFoldersModule.page_type === 'upload-list' || wpmfFoldersModule.page_type === 'upload-grid') {
                    let $tree = $('.wpmf-main-tree');
                    let tree_width = $tree.outerWidth() + 'px';
                    let $admin_bar_height = $('#wpadminbar').height();
                    if ($admin_bar_height > 32) {
                        $tree.css('top', $admin_bar_height + 'px');
                    }
                    $('#wpbody-content').css({'width': 'calc(100% - '+ tree_width +')','margin-left': tree_width, 'opacity': 1, 'padding-left': '20px', 'box-sizing': 'border-box'});
                    // trigger window resize to set attachments columns
                    if (wpmfFoldersModule.page_type === 'upload-grid') {
                        $(window).trigger('resize');
                    }
                    $('.rtl #wpbody-content').css({
                        'margin-right': (wpmfFoldersTreeModule.getTreeElement().outerWidth()) + 'px',
                        'margin-left': 0,
                        'opacity': 1,
                        'padding-right': '20px',
                        'box-sizing': 'border-box'
                    });
                    $tree.css({'opacity': 1});
                    // Remove the loader on list page
                    if (!$('.upload-php #posts-filter').hasClass('listview-loaded')) {
                        setTimeout(function () {
                            $('.upload-php #posts-filter').addClass('listview-loaded');
                        }, 200);
                    }
                }
            }, 200);
        },

        /**
         * Initialize folder tree resizing
         * @param $current_frame
         */
        initContainerResizing: function ($current_frame) {
            let is_resizing = false;
            const $body = $('body');
            
            $(window).on('resize', function () {
                if (!$('body').hasClass('rtl')) {
                    $('.wpmf-all-tree.scrollbar-inner').scrollbar();
                }
            });
            if (wpmf.vars.wpmf_pagenow === 'upload.php' && wpmfFoldersModule.page_type) {
                // Main upload.php page
                let $main = $('#wpbody');
                let $tree = $('.wpmf-main-tree');
                let $right_min_width = 500;
                let $tree_min_width = 250;

                const $handle = $('<div class="wpmf-main-tree-resize"></div>').appendTo($tree);
                $handle.on('mousedown', function (e) {
                    is_resizing = true;
                    $('body').css('user-select', 'none'); // prevent content selection while moving
                });

                let uploadPageTreeSize = wpmfFoldersModule.getCookie('upload-page-tree-size');
                if (uploadPageTreeSize < $tree_min_width) uploadPageTreeSize = $tree_min_width;
                if (typeof uploadPageTreeSize !== "undefined" && parseFloat(uploadPageTreeSize) > 0) {
                    $tree.css({'width': parseFloat(uploadPageTreeSize) + 'px'});
                }

                $(document).on('mousemove', function (e) {
                    // we don't want to do anything if we aren't resizing.
                    if (!is_resizing)
                        return;

                    // Calculate tree width
                    let tree_width = (e.clientX - $tree.offset().left);
                    if (tree_width < $tree_min_width) tree_width = $tree_min_width;
                    let right_width = $main.width() - tree_width;
                    if (right_width < $right_min_width) {
                        right_width = $right_min_width;
                        tree_width = $main.width() - $right_min_width;
                    }

                    $tree.css('width', tree_width + 'px');
                    // We have to set margin if we are in a fixed tree position or in list page
                    if ($('body').hasClass('rtl')) {
                        $('#wpbody-content').css({'width': right_width + 'px', 'margin-right': tree_width + 'px', 'padding-right': '20px', 'box-sizing': 'border-box'});
                    } else {
                        $('#wpbody-content').css({'width': right_width + 'px', 'margin-left': tree_width + 'px', 'padding-left': '20px', 'box-sizing': 'border-box'});
                    }
                    wpmfFoldersModule.setCookie('upload-page-tree-size', tree_width, 365);
                }).on('mouseup', function (e) {
                    if (is_resizing) {
                        // stop resizing
                        is_resizing = false;
                        $body.css('user-select', '');
                        $(window).trigger('resize');
                    }
                });
            } else {
                // Modal window with left menu
                const $right_min_width = 800;
                const $tree_min_width = 250;
                const $main = $('.media-modal-content:visible');
                const $menu = $current_frame.find('.media-frame-menu');
                const $tree = $current_frame.find('.wpmf-main-tree');
                const $handle = $('<div class="wpmf-main-tree-resize"></div>').appendTo($tree);
                const $right_cols = $current_frame.find('.media-frame-content, .media-frame-router,  .media-frame-title, .media-frame-toolbar');

                if ($menu) {
                    if ($current_frame.hasClass('wpmf_hide_media_menu')) {
                        $menu.find('.wpmf-all-tree').height($menu.height() - 180);
                    } else {
                        var a = $menu.find(".media-menu-item").length;
                        $menu.find('.wpmf-all-tree').height($menu.height() - 34 * a - 180);
                    }
                }
                $tree.css({'min-width': 0, 'border-right': 0});
                $handle.on('mousedown', function (e) {
                    is_resizing = true;
                    $body.css('user-select', 'none'); // prevent content selection while moving
                });

                let tree_width = wpmfFoldersModule.getCookie('menu-tree-size');
                if (tree_width < $tree_min_width) tree_width = $tree_min_width;
                if (typeof tree_width !== "undefined" && parseFloat(tree_width) > 0) {
                    $menu.css('width', parseFloat(tree_width) + 'px');
                    if ($('body').hasClass('rtl')) {
                        $right_cols.css('right', (parseFloat(tree_width) + 14) + 'px');
                    } else {
                        $right_cols.css('left', (parseFloat(tree_width) + 14) + 'px');
                    }
                }

                $(document).on('mousemove', function (e) {
                    // we don't want to do anything if we aren't resizing.
                    if (!is_resizing)
                        return;
                    let menu_width = (e.clientX - $menu.offset().left);
                    if (menu_width < $tree_min_width) menu_width = $tree_min_width;
                    let right_width = $main.width() - menu_width;
                    if (right_width < $right_min_width) {
                        menu_width = $main.width() - $right_min_width;
                    }

                    $menu.css('width', menu_width + 'px');
                    if ($('body').hasClass('rtl')) {
                        $right_cols.css('right', (parseFloat(menu_width) + 14) + 'px');
                    } else {
                        $right_cols.css('left', (parseFloat(menu_width) + 14) + 'px');
                    }
                    wpmfFoldersModule.setCookie('menu-tree-size', menu_width, 365);
                }).on('mouseup', function (e) {
                    if (is_resizing) {
                        // stop resizing
                        is_resizing = false;
                        $body.css('user-select', '');
                        $(window).trigger('resize');
                    }
                });
            }
        }
    };

    // Let's initialize WPMF folder tree features
    $(document).ready(function () {
        if (typeof wpmfFoldersModule !== "undefined") {
            if (wpmfFoldersModule.page_type === 'upload-list') {
                // Don't need to wait on list page
                wpmfFoldersTreeModule.initModule(wpmfFoldersModule.getFrame());
            } else {
                // Wait for the main wpmf module to be ready
                wpmfFoldersModule.on('ready', function ($current_frame) {
                    setTimeout(function () {
                        wpmfFoldersTreeModule.initModule($current_frame);
                    },200);
                });
            }
        }
    });
})(jQuery);

// call single click or double click on folder tree
jQuery.fn.wpmfSingleDoubleClick = function (single_click_callback, double_click_callback, timeout) {
    return this.each(function () {
        var clicks = 0, self = this;
        jQuery(this).on('click', function (event) {
            clicks++;
            if (clicks === 1) {
                setTimeout(function () {
                    if (clicks === 1) {
                        single_click_callback.call(self, event);
                    } else {
                        double_click_callback.call(self, event);
                    }
                    clicks = 0;
                }, timeout || 300);
            }
        });
    });
};
