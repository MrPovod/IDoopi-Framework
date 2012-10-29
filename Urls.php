<?php

require_once 'iDoopi.php';
require 'site/init.php';

$urlpatterns = array(
    "@^/idoopi/index.php$@" => "MainPageView::mainpage",
    "@^/idoopi/$@" => "MainPageView::mainpage",
    "@^/idoopi/about/$@" => "MainPageView::soon",
    "@^/idoopi/sourcecode/$@" => "MainPageView::soon",
    "@^/idoopi/documentation/$@" => "MainPageView::soon",
    "@^/idoopi/blog/$@" => "MainPageView::soon",
    //"@^/PandaORM/page/(?P<page_id>\d+)/$@" => "MainPage::mainpage"
    );
?>
