<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/spanner/README.md
 */

namespace Google\Cloud\Samples\Spanner;

// [START spanner_field_access_on_struct_parameters]
use Google\Cloud\Spanner\SpannerClient;
use Google\Cloud\Spanner\Database;
use Google\Cloud\Spanner\StructType;

/**
 * Queries sample data from the database using a struct field value.
 * Example:
 * ```
 * query_data_with_struct_field($instanceId, $databaseId);
 * ```
 *
 * @param string $instanceId The Spanner instance ID.
 * @param string $databaseId The Spanner database ID.
 */
function query_data_with_struct_field(string $instanceId, string $databaseId): void
{
    $spanner = new SpannerClient();
    $instance = $spanner->instance($instanceId);
    $database = $instance->database($databaseId);

    $nameType = (new StructType)
        ->add('FirstName', Database::TYPE_STRING)
        ->add('LastName', Database::TYPE_STRING);
    $results = $database->execute(
        'SELECT SingerId FROM Singers WHERE FirstName = @name.FirstName',
        [
            'parameters' => [
                'name' => [
                    'FirstName' => 'Elena',
                    'LastName' => 'Campbell'
                ]
            ],
            'types' => [
                'name' => $nameType
            ]
        ]
    );
    foreach ($results as $row) {
        printf('SingerId: %s' . PHP_EOL,
            $row['SingerId']);
    }
}
// [END spanner_field_access_on_struct_parameters]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
