<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::message'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('mail::message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
# <?php echo e($user->name); ?>, Welcome to notTV!

We are thrilled to you joined us where creativity knows no bounds, and every story matters. At notTV, we're more than just a platform; we're a community of visionary creators, united by a passion for sharing authentic stories and making a genuine impact.

## Empowering Your Creative Journey

At notTV, your voice is invaluable. Our mission is to empower you with the tools, platform, and community support needed to bring your visions to life. Whether you're here to inspire, educate, entertain, or provoke thought, notTV is your canvas.

We're excited to have you onboard. Please click the button in the next email to verify your email and get started on your creator journey.

<br>
<?php $__env->startComponent('mail::button', ['url' => route('dashboard')]); ?>
    Explore Your Dashboard
<?php echo $__env->renderComponent(); ?>
<br>

## Building Together

We are still in the early Beta stages of development. Your feedback and opinion matters. Only a handful of creators are on the platform at this time as we work out the bugs.

As part of the notTV family, you join a collaborative ecosystem of creators, viewers, and supporters all working towards a common goal - redefining the media landscape through decentralized, community-driven content. Your participation makes you an integral part of this movement.

## What's Next?

- **Explore**: Dive into your Dashboard to start creating, sharing, and engaging.
- **Connect**: Join the notTV community town halls and meet fellow creators.
- **Learn**: Access resources, tutorials, and workshops to enhance your skills.

We are here to support you every step of the way. If you have any questions, ideas, or need assistance, don't hesitate to reach out.

Thank you for choosing to embark on this creative journey with us. We can't wait to see the incredible stories you'll tell.

Warmest regards,<br>
The notTV Team
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb8f5c8a6ad1b73985c32a4b97acff83989288b9e)): ?>
<?php $component = $__componentOriginalb8f5c8a6ad1b73985c32a4b97acff83989288b9e; ?>
<?php unset($__componentOriginalb8f5c8a6ad1b73985c32a4b97acff83989288b9e); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/mail/welcome.blade.php ENDPATH**/ ?>