<?php

namespace App\Services\Agreement;

use App\Models\AgreementHistory;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Model\CarbonCopy;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\InlineTemplate;
use DocuSign\eSign\Model\CompositeTemplate;
use DocuSign\eSign\Model\DateSigned;
use DocuSign\eSign\Model\Email;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\FullName;
use DocuSign\eSign\Model\PhoneNumber;
use Exception;
use Illuminate\Support\Facades\DB;

class AgreementService
{
    use ResponseTrait;
    public function getAllData()
    {
        $agreementHistory = AgreementHistory::query()
            ->leftJoin('users', 'agreement_histories.recipient_id', '=', 'users.id')
            ->select('agreement_histories.*', 'users.first_name', 'users.last_name')
            ->where('agreement_histories.sender_id', auth()->id());

        return datatables($agreementHistory)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                return $data->first_name . ' ' . $data->last_name;
            })
            ->addColumn('date', function ($data) {
                return date('Y-m-d', strtotime($data->status_date_time));
            })
            ->addColumn('status', function ($data) {
                $status = ucfirst($data->status);
                if ($data->is_test == ACTIVE) {
                    $status .= " (" . __('Testing') . ")";
                }
                return $status;
            })
            ->addColumn('action', function ($data) {
                return '<div class="tbl-action-btns d-inline-flex">
                    <a href="' . route('owner.agreement.download', $data->envelope_id) . '" class="p-1 tbl-action-btn" title="' . __('Download') . '"><span class="iconify" data-icon="material-symbols:download"></span></a>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function tenantAgreements()
    {
        $agreementHistory = AgreementHistory::query()
            ->leftJoin('users', 'agreement_histories.sender_id', '=', 'users.id')
            ->select('agreement_histories.*', 'users.first_name', 'users.last_name')
            ->where('agreement_histories.recipient_id', auth()->id())
            ->get();

        return $agreementHistory;
    }

    public function send($request)
    {
        // dd($request);

        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath(env('DS_AUTH_SERVER'));
        try {
            $accessToken = $this->getToken($apiClient);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }

        try {
            $userInfo = $apiClient->getUserInfo($accessToken);
            $accountInfo = $userInfo[0]->getAccounts();
            $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . env('DS_ESIGN_URI_SUFFIX'));

            $user = User::query()->where('owner_user_id', getOwnerUserId())->where('role', USER_ROLE_TENANT)->find($request->user_id);
            if (is_null($user)) {
                throw new Exception(__('No tenant found'));
            }

            if (is_null(env('DS_STATUS') != ACTIVE)) {
                throw new Exception(__('Digital Agreement Disabled!'));
            }

            $request->merge(['email' => $user->email, 'name' => $user->name]);
            $envelopeDefinition = $this->buildEnvelope($request);
            $envelopeApi = new EnvelopesApi($apiClient);
            $result = $envelopeApi->createEnvelope($accountInfo[0]->getAccountId(), $envelopeDefinition);
            $recipient_id = $user->id;
            $this->historyCreate($result, $recipient_id);
            return $this->success([], __(SENT_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function historyCreate(
        $result,
        $recipient_id,
    ) {
        AgreementHistory::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient_id,
            'bulk_envelope_status' => $result['bulk_envelope_status'],
            'envelope_id' => $result['envelope_id'],
            'error_details' => $result['error_details'],
            'recipient_signing_uri' => $result['recipient_signing_uri'],
            'recipient_signing_uri_error' => $result['recipient_signing_uri_error'],
            'status' => $result['status'],
            'status_date_time' => $result['status_date_time'],
            'uri' => $result['uri'],
        ]);
    }

    private function buildEnvelope(Request $request): EnvelopeDefinition
    {
        $fileContent = $request->file('file')->get();
        $fileName = $request->file('file')->getClientOriginalName();
        $fileExtension = $request->file('file')->getClientOriginalExtension();
        $recipientEmail = $request['email'];
        $recipientName = $request['name'];

        $document = new Document(['document_id' => "1", 'document_base64' => base64_encode($fileContent), 'file_extension' => $fileExtension, 'name' => $fileName]);

        $sign_here_tab = new SignHere(['anchor_string' => "**signature**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);
        $full_name_tab = new FullName(['anchor_string' => "**full name**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);
        $first_name_tabs = new FullName(['anchor_string' => "**first name**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);
        $last_name_tabs = new FullName(['anchor_string' => "**last name**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);
        $phone_number_tab = new PhoneNumber(['anchor_string' => "**phone number**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);
        $date_signed_tabs = new DateSigned(['anchor_string' => "**date**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);
        $email_address_tabs = new Email(['anchor_string' => "**email**", 'anchor_units' => "pixels", 'anchor_x_offset' => "0", 'anchor_y_offset' => "0"]);

        $tabs1 = new Tabs(
            [
                'full_name_tabs' => [$full_name_tab],
                'sign_here_tabs' => [$sign_here_tab],
                'date_signed_tabs' => [$date_signed_tabs],
                'email_address_tabs' => [$email_address_tabs],
                'first_name_tabs' => [$first_name_tabs],
                'last_name_tabs' => [$last_name_tabs]
            ]
        );

        $signer = new Signer(['email' => $recipientEmail, 'name' =>  $recipientName, 'recipient_id' => "1", 'tabs' => $tabs1]);
        $CC = new CarbonCopy(['email' => auth()->user()->email, 'name' => auth()->user()->name, 'recipient_id' => "2", 'routing_order' => "2"]);
        $recipients = new Recipients(['signers' => [$signer], 'carbon_copies' => [$CC]]);
        $inline_template = new InlineTemplate(['recipients' => $recipients, 'sequence' => "1"]);
        $composite_template = new CompositeTemplate(['composite_template_id' => "1", 'document' => $document, 'inline_templates' => [$inline_template]]);
        $envelope_definition = new EnvelopeDefinition(['composite_templates' => [$composite_template], 'email_subject' => __('Please sign this document'), 'status' => "sent"]);
        return $envelope_definition;
    }

    private function getToken(ApiClient $apiClient): string
    {
        try {
            $privateKey = file_get_contents(storage_path(env('DS_KEY_PATH')), true);
            $response = $apiClient->requestJWTUserToken(
                $ikey = env('DS_CLIENT_ID'),
                $userId = env('DS_IMPERSONATED_USER_ID'),
                $key = $privateKey,
                $scope = env('DS_JWT_SCOPE')
            );
            $token = $response[0];
            $accessToken = $token->getAccessToken();
        } catch (Exception $e) {
            throw $e;
        }
        return $accessToken;
    }

    public function testSend($request)
    {
        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath(env('DS_AUTH_SERVER'));
        try {
            $accessToken = $this->getToken($apiClient);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }

        $userInfo = $apiClient->getUserInfo($accessToken);
        $accountInfo = $userInfo[0]->getAccounts();
        $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . env('DS_ESIGN_URI_SUFFIX'));

        $envelopeDefinition = $this->buildEnvelope($request);
        DB::beginTransaction();
        try {
            $envelopeApi = new EnvelopesApi($apiClient);
            $result = $envelopeApi->createEnvelope($accountInfo[0]->getAccountId(), $envelopeDefinition);
            AgreementHistory::create([
                'sender_id' => auth()->id(),
                'is_test' => ACTIVE,
                'bulk_envelope_status' => $result['bulk_envelope_status'],
                'envelope_id' => $result['envelope_id'],
                'error_details' => $result['error_details'],
                'recipient_signing_uri' => $result['recipient_signing_uri'],
                'recipient_signing_uri_error' => $result['recipient_signing_uri_error'],
                'status' => $result['status'],
                'status_date_time' => $result['status_date_time'],
                'uri' => $result['uri'],
            ]);
            DB::commit();
            return $this->success([], __(SENT_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function download($envelope_id)
    {
        $agreementHistory = AgreementHistory::query()
            ->where('sender_id', auth()->id())
            ->where('envelope_id', $envelope_id)
            ->first();

        if (is_null($agreementHistory)) {
            return back()->with('error', __('No Agreement found'));
        }

        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath(env('DS_AUTH_SERVER'));
        try {
            $accessToken = $this->getToken($apiClient);
        } catch (Exception $th) {
            return back()->with('error', $th->getMessage());
        }

        $userInfo = $apiClient->getUserInfo($accessToken);
        $accountInfo = $userInfo[0]->getAccounts();
        $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . env('DS_ESIGN_URI_SUFFIX'));
        try {
            $envelopeApi = new EnvelopesApi($apiClient);
            $result = $envelopeApi->getDocument($accountInfo[0]->getAccountId(), '1', $agreementHistory->envelope_id);
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=\"{$agreementHistory->envelope_id}.pdf\"");
            $file_path = $result->getPathname();
            readfile($file_path);
            exit();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function tenantDownload($envelope_id)
    {
        $agreementHistory = AgreementHistory::query()
            ->where('recipient_id', auth()->id())
            ->where('envelope_id', $envelope_id)
            ->first();

        if (is_null($agreementHistory)) {
            return back()->with('error', __('No Agreement found'));
        }

        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath(env('DS_AUTH_SERVER'));
        try {
            $accessToken = $this->getToken($apiClient);
        } catch (Exception $th) {
            return back()->with('error', $th->getMessage());
        }

        $userInfo = $apiClient->getUserInfo($accessToken);
        $accountInfo = $userInfo[0]->getAccounts();
        $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . env('DS_ESIGN_URI_SUFFIX'));
        try {
            $envelopeApi = new EnvelopesApi($apiClient);
            $result = $envelopeApi->getDocument($accountInfo[0]->getAccountId(), '1', $agreementHistory->envelope_id);
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=\"{$agreementHistory->envelope_id}.pdf\"");
            $file_path = $result->getPathname();
            readfile($file_path);
            exit();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function test($envelope_id)
    {
        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath(env('DS_AUTH_SERVER'));
        try {
            $accessToken = $this->getToken($apiClient);
        } catch (Exception $th) {
            return back()->with('error', $th->getMessage());
        }

        $userInfo = $apiClient->getUserInfo($accessToken);
        $accountInfo = $userInfo[0]->getAccounts();
        $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . env('DS_ESIGN_URI_SUFFIX'));
        try {
            $envelopeApi = new EnvelopesApi($apiClient);
            $result = $envelopeApi->getDocument($accountInfo[0]->getAccountId(), '1', $envelope_id);
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=\"test-file.pdf\"");
            ob_clean();
            flush();
            $file_path = $result->getPathname();
            readfile($file_path);
            exit();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
