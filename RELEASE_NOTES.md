# Release Notes

## Draft PR

### Potential regressions

- **`isFeatured` property of components**: existing configurations using `isFeatured: 2` to display all FAQs are no longer compatible with the new behaviour, where `null` represents no filter.
- They must be migrated to `isFeatured: null`.
- **Component translation keys**: several keys have been reorganised, notably from `aic.faq::lang.component.*` to `aic.faq::lang.components.*`. Custom translations, language overrides or themes that refer to the old keys may no longer be resolved and must be updated.
