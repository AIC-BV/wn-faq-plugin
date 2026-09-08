<?php

namespace Aic\Faq\Classes\Enums;


enum FeaturedStatusEnum: int
{
    protected const LANG_KEY = 'aic.faq::lang.enums.featured_status.%s';

    use \Aic\Faq\Classes\Traits\BackedEnum;

    case FEATURED = 1;
    case NOT_FEATURED = 0;
}