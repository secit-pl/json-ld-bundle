# JSON-LD Symfony Bundle

JSON-LD generator for the Symfony 2.8 and 3.0+.

## Installation

From the command line run

```
$ composer require secit-pl/json-ld-bundle
```

## Usage

### Basic Usage

First of all you need to create a Transformer which will transform the object to the data array.

```php
namespace Test\TestBundle\JsonLd;

use SecIT\JsonLdBundle\Transformer\TransformerInterface;

class TestTransformer implements TransformerInterface
{
    public function getContextType()
    {
        return 'thing';
    }

    public function transform($object)
    {
        return [
            'name' => $object->getName(),
        ];
    }
}
```

As the basis for this of this bundle is the https://github.com/Torann/json-ld so the transformer should return the context type and data accepted by the JSON-LD Generator.

Next you need to register the transformer as service using the secit.jsonld_transformer tag in your services.yml.

```yaml
services:
    test.object_transformer:
        class: Test\TestBundle\JsonLd\TestTransformer
        tags:
            - { name: secit.jsonld_transformer, class: Test\TestBundle\Classes\ClassToBeTransformedToJsonLd }
```

If you want to assign more than one class to the same transformer you can add multiple tags to the same service.

```yaml
services:
    test.object_transformer:
        class: Test\TestBundle\JsonLd\TestTransformer
        tags:
            - { name: secit.jsonld_transformer, class: Test\TestBundle\Classes\Class1 }
            - { name: secit.jsonld_transformer, class: Test\TestBundle\Classes\Class2 }
            - { name: secit.jsonld_transformer, class: Test\TestBundle\Classes\Class3 }
            ...
```

From now you can transform the specified in the tag class attribute object (in the following example the \Test\TestBundle\Classes\ClassToBeTransformedToJsonLd) to the JSON-LD as following:
 
```php
$object = new \Test\TestBundle\Classes\ClassToBeTransformedToJsonLd();
$object->setName('Some name');
echo $this->getContainer()->get('secit.json_ld')->generate($object);
```

The output should be something like this:

```html
<script type="application/ld+json">{"@context":"http:\/\/schema.org","@type":"Thing","name":"Some name"}</script>
```

### Advenced usage

In many situations it's required to have a nested transformers to not implement whole logic in the single class.
To use nested transformers your Transformer should implement JsonLdAwareInterface. If you don't want to implement
the interface methods by your own you can use the JsonLdAwareTrait.

Here is the simple example of how to use it:

AuthorTransformer.php
```php
namespace Test\TestBundle\JsonLd;

use SecIT\JsonLdBundle\DependencyInjection\JsonLdAwareInterface;
use SecIT\JsonLdBundle\DependencyInjection\JsonLdAwareTrait;
use SecIT\JsonLdBundle\Transformer\TransformerInterface;

class AuthorTransformer implements TransformerInterface
{
    public function getContextType()
    {
        return 'author';
    }

    public function transform($author)
    {
        return [
            'name' => $author->name,
        ];
    }
}
```

ArticleTransformer.php
```php
namespace Test\TestBundle\JsonLd;

use SecIT\JsonLdBundle\DependencyInjection\JsonLdAwareInterface;
use SecIT\JsonLdBundle\DependencyInjection\JsonLdAwareTrait;
use SecIT\JsonLdBundle\Transformer\TransformerInterface;

class ArticleTransformer implements TransformerInterface, JsonLdAwareInterface
{
    use JsonLdAwareTrait;

    public function getContextType()
    {
        return 'article';
    }

    public function transform($article)
    {
        return [
            'name' => $article->name,
            'author' => $this->getJsonLd()->transform($article->author),
        ];
    }
}
```

Example input object:
```php
$author = new Author();
$author->name = 'Jon Smith';

$article = new Article();
$article->name = 'Example article';
$article->author = $author;
```

Output:
```html
<script type="application/ld+json">{"@context":"http:\/\/schema.org","@type":"Article","name":"Example article","author":{"@type":"Person","name":"Jon Smith"}}</script>
```

### Twig Support

This bundle also provides the Twig extension which allow to render JSON-LD directly from the Twig templates.

TestController:

```php
namespace Test\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Test\TestBundle\TestClass\Class1;

class TestController extends Controller
{
    public function testAction()
    {
        return $this->render('TestBundle:Test:example.html.twig', [
            'object' => new Foo(),
        ]);
    }
}
```

example.html.twig:
```twig
{{ object|json_ld }}
```

Output:
```html
<script type="application/ld+json">{"@context":"http:\/\/schema.org", ... }</script>
```