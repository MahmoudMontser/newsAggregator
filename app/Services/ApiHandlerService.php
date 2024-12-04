<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiHandlerService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Make a GET request to the API and return the response
     *
     * @param string $service The name of the service (e.g., newsapi, opennews, etc.)
     * @param string $endpoint The API endpoint
     * @param array $params The query parameters to be sent with the request
     * @return mixed
     */
    public function getResponse(string $service, string $endpoint, array $params = [])
    {
        try {
            $serviceConfig = config("services.$service");

            if (!$serviceConfig) {
                throw new \Exception("Service configuration for '$service' not found.");
            }

            // Merge the API key and base URI from the service configuration
            $params = array_merge($params, [
                'apiKey' => $serviceConfig['api_key'],
            ]);

            // Send the GET request
            $response = $this->client->get($serviceConfig['base_uri'] . $endpoint, [
                'query' => $params,
            ]);

            // Decode the response body to an array
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Check for successful status code
            if ($response->getStatusCode() !== 200) {
                throw new \Exception("API request failed with status code {$response->getStatusCode()}");
            }

            return $responseData;

        } catch (RequestException $e) {
            // Handle request exception
            return $this->handleException($e);
        } catch (\Exception $e) {
            // Handle general exceptions
            return $this->handleException($e);
        }
    }

    /**
     * Handle exceptions and return a formatted response.
     *
     * @param \Exception $e
     * @return array
     */
    private function handleException(\Exception $e)
    {
        return [
            'error' => true,
            'message' => $e->getMessage(),
        ];
    }
}
