<?php
$PluginInfo['showAllEmojis'] = [
    'Name' => 'Show All Emojis',
    'Description' => 'Add button to reveal all emojis in dropdown',
    'Version' => '0.0.3',
    'RequiredApplications' => ['Vanilla' => '>= 2.3'],
    'RequiredPlugins' => ['editor' => '>= 1.8.1'],
    'SettingsPermission' => 'Garden.Settings.Manage',
    'SettingsUrl' => '/dashboard/settings/showallemojis',
    'MobileFriendly' => true,
    'HasLocale' => true,
    'Author' => 'Robin Jurinka',
    'AuthorUrl' => 'http://vanillaforums.org/profile/r_j',
    'License' => 'MIT'
];

/**
 * Adds a "show all" button to the emoji drop down.
 *
 * Shows a toggle that will expand the emoji drop down to show all available
 * emojis.
 *
 * Open issues:
 * - Doesn't work with "edit comments" since that editor doesn't exist on page
 *   load. This solution must bind to "something" in order to always work...
 */
class ShowAllEmojisPlugin extends Gdn_Plugin {
    public function base_render_before($sender) {
        // Attach to all pages.
        $sender->addJsFile('showallemojis.js', 'plugins/showAllEmojis');
        // Get all emojis which are not already displayed.
        $emoji = Emoji::instance();
        $hiddenEmojis = array_diff_key(
            $emoji->getEmoji(),
            array_flip($emoji->getEditorList())
        );
        // Get their image tag markup and add it to an array.
        $emojiInfo = [];
        foreach ($hiddenEmojis as $emojiAlias => $emojiCanonical) {
            $emojiInfo[] = [
                'alias' => $emojiAlias,
                'html' => $emoji->img(
                    $emoji->getEmojiPath($emojiAlias),
                    $emojiAlias
                )
            ];
        }

        // Add everything to definitios so that it can be retrieved via js.
        $sender->addDefinition('HiddenEmojis', $emojiInfo);
        $sender->addDefinition('ShowAll', t('Show All'));
        $sender->addDefinition('ShowLess', t('Show Less'));
    }
}
