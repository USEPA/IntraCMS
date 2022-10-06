<?php

namespace Drupal\epa_web_areas\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\group\Access\CalculatedGroupPermissionsItemInterface;
use Drupal\group\Access\ChainGroupPermissionCalculatorInterface;
use Drupal\group\Access\GroupPermissionCheckerInterface;
use Drupal\group\Entity\GroupInterface;

/**
 * Calculates group permissions for an account.
 */
class GroupPermissionChecker implements GroupPermissionCheckerInterface {

  protected $innerService;
  protected $groupPermissionCalculator;

  public function __construct(GroupPermissionCheckerInterface $inner_service, ChainGroupPermissionCalculatorInterface $permission_calculator) {
    $this->innerService = $inner_service;
    $this->groupPermissionCalculator = $permission_calculator;
  }

  /**
   * @inheritDoc
   * @see \Drupal\group\Access\GroupPermissionChecker
   */
  public function hasPermissionInGroup($permission, AccountInterface $account, GroupInterface $group) {
    $usual_result = $this->innerService->hasPermissionInGroup($permission, $account, $group);

    // We only want to remove access; if access is already denied then return.
    if (!$usual_result) {
      return $usual_result;
    }

    // @TODO: If we have to do an additional node type look to refactor this into a single field.
    $news_release_permissions = [
      'create group_node:news_release entity',
      'update any group_node:news_release entity',
      'delete any group_node:news_release entity',
      'delete own group_node:news_release entity',
      'update any group_node:news_release entity',
      'update own group_node:news_release entity',
    ];

    if (in_array($permission, $news_release_permissions) &&
      $group->hasField('field_allow_news_releases')) {
      return $group->field_allow_news_releases->value;
    }

    $commentary_permissions = [
      'create group_node:perspective entity',
      'update any group_node:perspective entity',
      'delete any group_node:perspective entity',
      'delete own group_node:perspective entity',
      'update any group_node:perspective entity',
      'update own group_node:perspective entity',
    ];

    if (in_array($permission, $commentary_permissions) &&
      $group->hasField('field_allow_perspectives')) {
      return $group->field_allow_perspectives->value;
    }
    return $usual_result;
  }

}
