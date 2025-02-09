(function () {
    $('document').ready(function () {
        var side_bar = $('#sidenav');
        var $linkSidebarActive = side_bar.find('li > a[href="' + location + '"]');
        if ($linkSidebarActive.length) {
            $linkSidebarActive.parents('li').not('.quick-links').addClass('active');
            // Set aria expanded to true
            $linkSidebarActive.prop('aria-expanded', true);
            $linkSidebarActive.parents('ul.nav-second-level').prop('aria-expanded', true);
            $linkSidebarActive.parents('li').find('a:first-child').prop('aria-expanded', true);
        }

        $('#main-header-search-button').click(function () {
            if (!$('.search-section').hasClass('show')) {
                $('.search-section').addClass('show');

                if (is_mobile())
                    $('.m-hidden-search').addClass('hide');
            } else {
                $('.search-section').removeClass('show');
                if (is_mobile())
                    $('.m-hidden-search').removeClass('hide');
            }
        });

        // Prevent default for empty links
        $('[href="#"]').click(function (e) {
            e.preventDefault()
        });

        // Adding data-parent to togglable nav items
        $('#sidenav [data-toggle]').each(function () {
            $(this).attr('data-parent', '#' + $(this).parent().parent().attr('id'));
        });

        /**
         * Trigger window resize to update nvd3 charts
         */
        $('[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            window.dispatchEvent(new Event('resize'));
        });

        /**
         * Enable tooltips everywhere
         */
        $('[data-toggle="tooltip"]').tooltip();

        /**
         * Enable popovers everywhere
         */
        $('[data-toggle="popover"]').popover();

        // Activate animated progress bar
        $('.bd-toggle-animated-progress').on('click', function () {
            $(this).siblings('.progress').find('.progress-bar-striped').toggleClass('progress-bar-animated')
        });

        /**
         * Enable Custom Scrollbars only for desktop
         */
        var mobileDetect = new MobileDetect(window.navigator.userAgent);

        if (!mobileDetect.mobile()) {
            $('.custom-scrollbar').each(function () {
                new PerfectScrollbar(this);
            })

            $('#task-modal').on('shown.bs.modal', function () {
                $('#task-modal .custom-scrollbar').each(function () {
                    new PerfectScrollbar(this);
                })
            })
        }
        else {
            $('body').addClass('is-mobile');
        }

        /**
         * Fix code block indentations
         */
        $('code').each(function () {
            var lines = $(this).html().split('\n');

            if (lines[0] === '') {
                lines.shift()
            }

            var matches;
            var indentation = (matches = /^\s+/.exec(lines[0])) !== null ? matches[0] : null;

            if (!!indentation) {
                lines = lines.map(function (line) {
                    return line.replace(indentation, '');
                });

                $(this).html(lines.join('\n').trim());
            }
        });

        /**
         * Flip source-preview cards
         */
        $('.toggle-source-preview').on('click', function () {
            $(this).parents('.example').toggleClass('show-source');
        });
    });

    /**
     * Data tables fix header resize
     */
    $(window).on('resize', function () {
        $.fn.dataTable.tables({
            visible: true,
            api: true
        }).columns.adjust();
    });

    /**
     *     PNotify default styling.
     */
    PNotify.defaults.styling = 'material';
    PNotify.defaults.icons = 'material';

    /**
     * Watching media step changes
     */
    /*$(window).on('fuse::matchMediaChanged', function (ev, currentStep, isOrBelow, isOrAbove)
     {
     console.info('match media changed');
     console.info(currentStep);
     });*/

})();