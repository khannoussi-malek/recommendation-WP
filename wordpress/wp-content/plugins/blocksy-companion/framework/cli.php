<?php

namespace Blocksy;

/*
add_action('blocksy:customizer:load:before', function () {
	$_REQUEST['wp_customize'] = 'on';
	_wp_customize_include();

	global $wp_customize;

	$wp_customize->wp_loaded();
});
 */

class Cli {
	public function __construct() {
		/**
		 * Move all widgets to the inactive widgets area.
		 */
		\WP_CLI::add_command('blocksy widgets drop', function ($args) {
			$sidebars_widgets = get_option('sidebars_widgets', array());

			if (! isset($sidebars_widgets['wp_inactive_widgets'])) {
				$sidebars_widgets['wp_inactive_widgets'] = [];
			}

			foreach ($sidebars_widgets as $sidebar_id => $widgets) {
				if (! $widgets) continue;
				if ($sidebar_id === 'wp_inactive_widgets') {
					continue;
				}

				if ($sidebar_id === 'array_version') {
					continue;
				}

				foreach ($widgets as $widget_id) {
					$sidebars_widgets['wp_inactive_widgets'][] = $widget_id;
				}

				$sidebars_widgets[$sidebar_id] = [];
			}

			update_option('sidebars_widgets', $sidebars_widgets);
			unset($sidebars_widgets['array_version']);

			set_theme_mod('sidebars_widgets', [
				'time' => time(),
				'data' => $sidebars_widgets
			]);
		});

		/**
		 * Kick start the demo import process.
		 *
		 * ## OPTIONS
		 *
		 * <demo>
		 * : The demo name.
		 *
		 * <builder>
		 * : The builder name. Default to `gutenberg`.
		 */
		\WP_CLI::add_command('blocksy demo import:start', function ($cli_argv) {
			$args = $this->get_demo_args($cli_argv);

			Plugin::instance()->demo->set_current_demo(
				$args['demo'] . ':' . $args['builder']
			);

			$demo_data = Plugin::instance()->demo->fetch_single_demo([
				'demo' => $args['demo'],
				'builder' => $args['builder']
			]);
		});

		/**
		 * Import the plugins required by the demo.
		 *
		 * ## OPTIONS
		 *
		 * <demo>
		 * : The demo name.
		 *
		 * <builder>
		 * : The builder name. Default to `gutenberg`.
		 */
		\WP_CLI::add_command('blocksy demo import:plugins', function ($cli_argv) {
			$args = $this->get_demo_args($cli_argv);

			$demo_data = Plugin::instance()->demo->fetch_single_demo([
				'demo' => $args['demo'],
				'builder' => $args['builder']
			]);

			$plugins = new DemoInstallPluginsInstaller([
				'has_streaming' => false,
				'plugins' => implode(':', $demo_data['plugins'])
			]);

			$plugins->import();
		});

		/**
		 * Import the options required by the demo.
		 *
		 * ## OPTIONS
		 *
		 * <demo>
		 * : The demo name.
		 *
		 * <builder>
		 * : The builder name. Default to `gutenberg`.
		 */
		\WP_CLI::add_command('blocksy demo import:options', function ($cli_argv) {
			$args = $this->get_demo_args($cli_argv);

			$options = new DemoInstallOptionsInstaller([
				'has_streaming' => false,
				'demo_name' => $args['demo'] . ':' . $args['builder']
			]);

			$options->import();
		});

		/**
		 * Import the widgets required by the demo.
		 *
		 * ## OPTIONS
		 *
		 * <demo>
		 * : The demo name.
		 *
		 * <builder>
		 * : The builder name. Default to `gutenberg`.
		 */
		\WP_CLI::add_command('blocksy demo import:widgets', function ($cli_argv) {
			$args = $this->get_demo_args($cli_argv);

			$widgets = new DemoInstallWidgetsInstaller([
				'has_streaming' => false,
				'demo_name' => $args['demo'] . ':' . $args['builder']
			]);

			$widgets->import();
		});

		/**
		 * Import the content required by the demo.
		 *
		 * ## OPTIONS
		 *
		 * <demo>
		 * : The demo name.
		 *
		 * <builder>
		 * : The builder name. Default to `gutenberg`.
		 */
		\WP_CLI::add_command('blocksy demo import:content', function ($cli_argv) {
			$args = $this->get_demo_args($cli_argv);

			$content = new DemoInstallContentInstaller([
				'has_streaming' => false,
				'demo_name' => $args['demo'] . ':' . $args['builder']
			]);

			$content->import();
		});

		/**
		 * Clean the currently installed demo.
		 */
		\WP_CLI::add_command('blocksy demo clean', function ($cli_argv) {
			update_option('blocksy_ext_demos_current_demo', null);

			$eraser = new DemoInstallContentEraser([
				'has_streaming' => false
			]);

			$eraser->import();
		});

		/**
		 * Finish the demo import process.
		 */
		\WP_CLI::add_command('blocksy demo import:finish', function ($args) {
			$finish = new DemoInstallFinalActions([
				'has_streaming' => false
			]);

			$finish->import();
		});
	}

	private function get_demo_args($cli_argv) {
		if (empty($cli_argv)) {
			echo 'Please provide demo name.';
			exit;
		}

		if (! isset($cli_argv[1])) {
			$cli_argv[1] = '';
		}

		return [
			'demo' => $cli_argv[0],
			'builder' => $cli_argv[1]
		];
	}
}

