Workflow is as follows:

- go to login page (/openid_login)
- enter OpenID
- submit calls:
  - OpenIdLoginForm->validateForm
    - invokes openid_begin in openid.module
      - performs discovery via openid_discovery in openid.module
        - invokes openid_discovery_method_info
	- invokes _openid_xri_discovery
        - invokes _openid_xrds_discovery
	  - requests x-xrds-location from openid provider
      - calls openid_association() in openid.module
        - requests openid_association from openid provider via http-request
      - starts openid_authentication_request() in openid.module
        - generates request array
        - invokes openid from other implementations (?)
      - calls openid_redirect for service version 2
        - directly renders form, that is submitted to openid provider
	  - contains (amongst others) return-to: http://oauth.keawe.de/openid/authenticate?destination=user
      - calls openid_redirect_http for service version != 2
- openid_redirect form submutted via javascript, returns the user to site/openid/authenticate


