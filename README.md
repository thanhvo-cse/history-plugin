# History Plugin

Add history to models.

## Add history

Every updates on a model could be tracked as revisions, with user, time and changes in detail.

### Installation

To install using October CMS v3.1 or above:

```
composer require thanhvocse/history-plugin
php artisan october:migrate
```

## Controller Behavior

Configuring the Relation Behavior and History Behavior

```php
class TestReport extends Controller
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

Then define the history relation. The histories relation config below is optional only when you want to customize it.

```yaml
# config_relation.yaml
histories:
    view:
        recordsPerPage: 10
        defaultSort:
            - column: version
            - direction: desc
    readOnly: true
```

## Displaying a Relation Manager
The relation manager can then be displayed for a specified relation definition by calling the relationRender method. For example, if you want to display the relation manager on the Preview page, the preview.php view contents could look like this.

```php
<?= $this->formRenderPreview() ?>

<?= $this->relationRender('histories') ?>
```
