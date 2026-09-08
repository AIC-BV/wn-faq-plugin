<?php

namespace Aic\Faq\Classes\Traits;

/**
 * HasPublishStatus trait
 */
trait HasPublishStatus
{
    /**
     * Set the URL for this record instance
     */
    public function getPublishStatusOptions(): array
    {
        return \Aic\Faq\Classes\Enums\PublishStatusEnum::namesTranslated();
    }
}