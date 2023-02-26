<?php

$aliases = [
    ReactMoreTech\Analytics\Contracts\Support\Arrayable::class => Illuminate\Contracts\Support\Arrayable::class,
    ReactMoreTech\Analytics\Contracts\Support\Jsonable::class => Illuminate\Contracts\Support\Jsonable::class,
    ReactMoreTech\Analytics\Contracts\Support\Htmlable::class => Illuminate\Contracts\Support\Htmlable::class,
    ReactMoreTech\Analytics\Contracts\Support\CanBeEscapedWhenCastToString::class => Illuminate\Contracts\Support\CanBeEscapedWhenCastToString::class,
    ReactMoreTech\Analytics\Support\Arr::class => Illuminate\Support\Arr::class,
    ReactMoreTech\Analytics\Support\Collection::class => Illuminate\Support\Collection::class,
    ReactMoreTech\Analytics\Support\Enumerable::class => Illuminate\Support\Enumerable::class,
    ReactMoreTech\Analytics\Support\HigherOrderCollectionProxy::class => Illuminate\Support\HigherOrderCollectionProxy::class,
    ReactMoreTech\Analytics\Support\LazyCollection::class => Illuminate\Support\LazyCollection::class,
    ReactMoreTech\Analytics\Support\Traits\EnumeratesValues::class => Illuminate\Support\Traits\EnumeratesValues::class,
];

# echo "\n\n-- Aliasing....\n---------------------------------------------\n\n";

foreach ($aliases as $tighten => $illuminate) {
    if (! class_exists($illuminate) && ! interface_exists($illuminate) && ! trait_exists($illuminate)) {
        # echo "Aliasing {$tighten} to {$illuminate}.\n";
        class_alias($tighten, $illuminate);
    }
}
