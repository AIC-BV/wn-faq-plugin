<?php

namespace Aic\Faq\Classes\Enums;


enum PublishStatusEnum: int
{
    protected const LANG_KEY = 'aic.faq::lang.enums.publish_status.%s';

    use \Aic\Faq\Classes\Traits\BackedEnum;

    case PUBLISHED = 1;
    case IN_DRAFT = 2;
    case NOT_PUBLISHED = 0;
}