<?php
namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizeModelTrait {
  /**
   * @throws AuthorizationException
   */
  protected function authorizeModel($modelType, $modelId, $action): \Illuminate\Auth\Access\Response|false {
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
