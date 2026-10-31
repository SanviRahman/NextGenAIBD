<style>
    /* admin dashboard sidebar menu */
    .active_menu,
    .active_menu i {
        background: #593CC1;
        color: #ffffff !important;
    }
    .active_menu a {
        background: #593CC1;
        color: #ffffff !important;
    }

    #sideNavbar li.border {
        border: none !important;
        border-bottom: 1px dotted #e2e2e2 !important;
    }

    /* Homepage management dropdown - scoped to the backend sidebar. */
    #sideNavbar .hm-sidebar-toggle {
        display: flex;
        align-items: center;
        gap: 0.15rem;
        padding: 0.85rem 1rem;
        font-weight: 700;
        color: #182035 !important;
        background: #f6f3ff;
        border-left: 3px solid #6a48df;
        text-decoration: none;
    }
    #sideNavbar .hm-sidebar-toggle:hover,
    #sideNavbar .hm-sidebar-toggle.hm-sidebar-current {
        background: #eee8ff;
        color: #593cc1 !important;
    }
    #sideNavbar .hm-sidebar-toggle .hm-sidebar-arrow {
        font-size: 0.78rem;
        transition: transform 0.25s ease;
    }
    #sideNavbar .hm-sidebar-toggle[aria-expanded="true"] .hm-sidebar-arrow {
        transform: rotate(180deg);
    }
    #sideNavbar .hm-sidebar-panel {
        background: #f9f8fe;
        border-left: 3px solid #ded5ff;
    }
    #sideNavbar .hm-sidebar-panel .nav-link {
        font-size: 0.86rem;
        padding: 0.65rem 0.7rem 0.65rem 1.7rem;
    }
    #sideNavbar .hm-sidebar-panel .nav-link:hover:not(.active_menu) {
        background: #eee8ff;
        color: #593cc1 !important;
    }
    #sideNavbar .hm-sidebar-panel .nav-link.active_menu {
        background: #593cc1;
        color: #fff !important;
    }
    #sideNavbar .hm-sidebar-panel .nav-link.active_menu i {
        color: #fff !important;
    }
    @media (prefers-reduced-motion: reduce) {
        #sideNavbar .hm-sidebar-toggle .hm-sidebar-arrow { transition: none; }
    }
</style>
