<?php
declare(strict_types=1);

namespace App\Rector\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use Rector\Core\NodeManipulator\PropertyManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeNameResolver\NodeNameResolver\PropertyNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Removing\NodeManipulator\ComplexNodeRemover;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class RemoveUnusedPrivatePropertyRector extends AbstractRector
{
    private PropertyManipulator $propertyManipulator;
    private ComplexNodeRemover $complexNodeRemover;
    private PropertyNameResolver $propertyNameResolver;

    public function __construct(
        PropertyManipulator  $propertyManipulator,
        ComplexNodeRemover   $complexNodeRemover,
        PropertyNameResolver $propertyNameResolver,
    ) {
        $this->propertyManipulator = $propertyManipulator;
        $this->complexNodeRemover = $complexNodeRemover;
        $this->propertyNameResolver = $propertyNameResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused private properties except translations and images', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    private $property;

    private $translations;

    private $images;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    private $translations;

    private $images;
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipProperty($node)) {
            return null;
        }

        if ($this->propertyManipulator->isPropertyUsedInReadContext($node)) {
            return null;
        }

        if (\in_array($this->propertyNameResolver->resolve($node), ['translations', 'images'], true)) {
            return null;
        }

        $this->complexNodeRemover->removePropertyAndUsages($node);

        return $node;
    }

    private function shouldSkipProperty(Property $property): bool
    {
        if (1 !== \count($property->props)) {
            return true;
        }
        if (!$property->isPrivate()) {
            return true;
        }
        /** @var Class_|Interface_|Trait_|null $classLike */
        $classLike = $property->getAttribute(AttributeKey::CLASS_NODE);

        return !$classLike instanceof Class_;
    }
}
