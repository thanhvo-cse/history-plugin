# Model History Plugin
- Add revision history to October CMS backend.
- Every updates on a model could be tracked as revisions, with user, time and changes in detail.

# Requirements
- October CMS 1.0 or above

## Installation Instructions
```
php artisan plugin:install ThanhVoCSE.History
```
Or
```
composer require thanhvocse/history-plugin
php artisan october:migrate
```

## Controller Behavior
Configuring the Relation Behavior and History Behavior

```php
class Users extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
        \ThanhVoCSE\History\Behaviors\HistoryController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
}
```

Then define the histories relation. The histories relation config below is optional only when you want to customize it.

```yaml
# config_relation.yaml
histories:
    view:
        recordsPerPage: 10
        defaultSort:
            - column: version
            - direction: desc
```

## Displaying a Relation Manager
The relation manager can then be displayed for a specified relation definition by calling the relationRender method. For example, if you want to display the relation manager on the Preview page, the preview.php view contents could look like this.

```php
<?= $this->formRenderPreview() ?>

<?= $this->relationRender('histories') ?>
```

## Plugin Settings
This plugin creates a Settings menu item, found by navigating to Settings > Backend > Model History Settings.
- **History Rotation**: maximum number of revisions on a single model record, exceeding this number, the oldest revision will be deleted. Set it as 0 if you don't want to delete any old revisions.
