<?php namespace Bedard\Shop\Console;

use Bedard\Shop\Models\Cart;
use Illuminate\Console\Command;
use Lang;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Abandon extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'shop:abandon';

    /**
     * @var string The console command description.
     */
    protected $description;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->description = Lang::get('bedard.shop::lang.console.abandon.description');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        Cart::processAbandoned();

        $this->output->writeln('<info>' . Lang::get('bedard.shop::lang.console.abandon.success') . '</info>');
    }
}
