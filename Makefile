mercure_serve:
	SERVER_NAME=:3000 MERCURE_PUBLISHER_JWT_KEY=’hee’ MERCURE_SUBSCRIBER_JWT_KEY=’heyy’ ./mercure run -config Caddyfile.dev
