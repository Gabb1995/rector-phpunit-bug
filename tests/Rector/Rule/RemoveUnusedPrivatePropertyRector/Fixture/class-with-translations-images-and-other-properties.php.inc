<?php

namespace App\Tests\Rector\Rule\RemoveUnusedPrivatePropertyRector\Fixture;

final class ClassWithTranslationsImagesAndOtherProperties
{
    private $property;

    private $translations;

    private $images;
}
?>

-----
<?php

namespace App\Tests\Rector\Rule\RemoveUnusedPrivatePropertyRector\Fixture;

final class ClassWithTranslationsImagesAndOtherProperties
{
    private $translations;

    private $images;
}
?>
