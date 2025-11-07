<?php

namespace App\Controllers;

use WeatherApp\Core\ViewInterface;
use App\Helpers\UnitsHelper;

/**
 * HomeController
 *
 * This controller handles the home page for the application.
 */
class HomeController
{
    /**
     * @var ViewInterface Interface that specifies a method for rendering views
     */
    private ViewInterface $view;
    /**
     * @var string Page title
     */
    private string $pageTitle;

    /**
     * @var string Category of units used e.g. imperial, or metric
     */
    private string $unitsCategory;

    /**
     * Constructor
     *
     * Inject view interface, page title and units category
     *
     * @param ViewInterface $view
     * @param string $pageTitle
     * @param string $unitsCategory
     */
    public function __construct(ViewInterface $view, string $pageTitle, string $unitsCategory)
    {
        $this->view = $view;
        $this->unitsCategory = $unitsCategory;
        $this->pageTitle = $pageTitle;
    }

    /**
     * Show home page
     *
     * @return string Returns HTML content for the home page.
     */
    public function index(): string
    {
        // Map units for display based on the configured units category
        $mappedUnits = UnitsHelper::getUnits($this->unitsCategory);

        return $this->view->render('home.php', [
            'pageTitle' => $this->pageTitle,
            'temperatureUnit' => $mappedUnits['temperature'],
            'windUnit' => $mappedUnits['wind'],
        ]);
    }
}
