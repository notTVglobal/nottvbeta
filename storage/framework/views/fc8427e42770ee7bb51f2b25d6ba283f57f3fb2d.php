<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'url',
    'color' => 'primary', // Ensure this class is defined in your email CSS (inlined)
    'align' => 'center',
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'url',
    'color' => 'primary', // Ensure this class is defined in your email CSS (inlined)
    'align' => 'center',
]); ?>
<?php foreach (array_filter(([
    'url',
    'color' => 'primary', // Ensure this class is defined in your email CSS (inlined)
    'align' => 'center',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<!-- Start of the Button Component -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="<?php echo e($align); ?>" style="margin: auto;">
    <tr>
        <td style="border-radius: 5px; background: <?php echo e($color == 'primary' ? '#3490dc' : '#ffffff'); ?>; text-align: center;">
            <a href="<?php echo e($url); ?>" target="_blank" rel="noopener" style="background: <?php echo e($color == 'primary' ? '#3490dc' : '#ffffff'); ?>; border: 1px solid <?php echo e($color == 'primary' ? '#3490dc' : '#ffffff'); ?>; font-family: sans-serif; font-size: 15px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 5px; padding: 10px 25px; color: <?php echo e($color == 'primary' ? '#ffffff' : '#000000'); ?>;" class="button">
                <?php echo e($slot); ?>

            </a>
        </td>
    </tr>
</table>
<!-- End of the Button Component -->
<?php /**PATH /var/www/html/resources/views/vendor/mail/html/button.blade.php ENDPATH**/ ?>