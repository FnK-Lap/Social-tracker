<?php

namespace SocialTracker\Bundle\ApplicationBundle\Facebook;

use Facebook\FacebookRedirectLoginHelper;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RedirectLoginHelper extends FacebookRedirectLoginHelper
{
    const SESSION_PREFIX = 'fb_';

    /**
     * @var string State token for CSRF validation
     */
    protected $state;

    private $session;

    public function __construct(SessionInterface $session, $redirectUrl, $appId = null, $appSecret = null)
    {
        parent::__construct($redirectUrl, $appId, $appSecret);
        $this->session = $session;
    }

    // /**
    //  * Check if a redirect has a valid state.
    //  *
    //  * @return bool
    //  */
    // protected function isValidRedirect()
    // {
    //     return $this->getCode() && isset($_GET['state'])
    //             && $_GET['state'] == $this->state;
    // }

    // /**
    //  * Return the code.
    //  *
    //  * @return string|null
    //  */
    // protected function getCode()
    // {
    //     return isset($_GET['code']) ? $_GET['code'] : null;
    // }

    /**
     * Stores a state string in session storage for CSRF protection.
     * Developers should subclass and override this method if they want to store
     *   this state in a different location.
     *
     * @param string $state
     *
     * @throws FacebookSDKException
     */
    protected function storeState($state)
    {
        $this->session->set(self::SESSION_PREFIX . 'state', $state);
    }

    /**
     * Loads a state string from session storage for CSRF validation.  May return
     *   null if no object exists.  Developers should subclass and override this
     *   method if they want to load the state from a different location.
     *
     * @return string|null
     *
     * @throws FacebookSDKException
     */
    protected function loadState()
    {
        $key = self::SESSION_PREFIX . 'state';
        if ($this->session->has($key)) {
            $this->state = $this->session->get($key);
            return $this->state;
        }
        return null;
    }
}
