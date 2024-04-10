<?php
namespace App\Support\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Scheme;

class CustomPolicy extends Basic
{
    public function configure()
    {
        parent::configure();

//        $this->reportOnly();

//        if (app()->environment() === 'local') {
//            $this
//                ->addDirective(Directive::BASE, 'nottv.local:*')
//                ->addDirective(Directive::CONNECT, 'nottv.local.test:*')
//                ->addDirective(Directive::DEFAULT, 'nottv.local.test:*')
//                ->addDirective(Directive::FORM_ACTION, 'nottv.local.test:*')
//                ->addDirective(Directive::IMG, 'nottv.local.test:*')
//                ->addDirective(Directive::MEDIA, 'nottv.local.test:*')
//                ->addDirective(Directive::OBJECT, 'nottv.local.test:*')
//                ->addDirective(Directive::SCRIPT, 'nottv.local.test:*')
//                ->addDirective(Directive::STYLE, 'nottv.local.test:*');
//        }
//
//        $this
//            ->addDirective(Directive::CONNECT, Scheme::WSS)
//            ->addDirective(Directive::IMG, Scheme::DATA)
//            ->addDirective(Directive::SCRIPT, [
//
//            ])
//            ->addDirective(Directive::STYLE, [
//
//            ])
//            ->addDirective(Directive::FONT, [
//
//            ])
//            ->addNonceForDirective(Directive::SCRIPT)
//            ->addNonceForDirective(Directive::STYLE);
//
//        if (app()->environment() === 'local') {
//            $this
//                ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
//                ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
//        }

        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, [
                Keyword::SELF,
                'https://mist2.not.tv',
                'ws:',
                'wss:'
            ])
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [
                Keyword::SELF,
//                'data:',
//                'blob:',
                'https://ui-avatars.com',
            ])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
//                'http://nottv.local:8000',
//                'https://*.not.tv',
//                'https://*.stripe.com',
//                'https://*.stripe.network',
                Keyword::UNSAFE_INLINE,
//                Keyword::UNSAFE_EVAL
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE
            ])
            ->addDirective(Directive::OBJECT, Keyword::NONE);
//            ->addNonceForDirective(Directive::SCRIPT)
//            ->addNonceForDirective(Directive::STYLE);

//        if (app()->environment() === 'local') {
//            $this
//                ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
//                ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
//        }

    }

}
