<?php
namespace App\Traits;

trait AuthorizeModelTrait {
  protected function authorizeModel($modelType, $modelId, $action) {
    if (!$modelType || !$modelId) {
      return false;
    }

    $modelClass = '\\App\\Models\\' . ucfirst($modelType);
    if (!class_exists($modelClass)) {
      return false;
    }

    $model = $modelClass::find($modelId);
    if (!$model) {
      return false;
    }

    return $this->authorize($action, $model);
  }
}
