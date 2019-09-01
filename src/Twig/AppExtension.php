<?php
namespace App\Twig;

use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * AppExtension constructor.
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return [
            'locale' => $this->locale
        ];
    }

    /**
     * @return array|\Twig\TwigTest[]
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest(
                'like',
                function($obj) {
                    return $obj instanceof LikeNotification;
                }
            )
        ];
    }
}
