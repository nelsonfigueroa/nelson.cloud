+++
title = "How to Tell What Kind of Pulumi Access Token You Have"
summary = "Pulumi access tokens all look the same but you can use the Pulumi CLI or API to figure out what kind of token it is."
date = "2026-08-03"
categories = ["Pulumi"]
ShowToc = true
TocOpen = true
featured = false
+++

Pulumi Cloud has three kinds of access tokens:
- personal tokens
- organization tokens
- team tokens

All three kinds of tokens look similar and have the same format: `pul-` followed by 40 characters (at least according to [Gitleaks](https://github.com/gitleaks/gitleaks/blob/b58d3f102cf3a2c84cb7f923d05c25c9b1aed84b/config/gitleaks.toml#L2804) and [TruffleHog](https://github.com/trufflesecurity/trufflehog/blob/82df476e759c1448517fac6bfb3677685cdcd78a/pkg/detectors/pulumi/pulumi.go#L25)). So if you find a random token in something like an old CI pipeline, the token string itself doesn't tell you where it's from, what it can do, or who it belongs to.

However, you can use the Pulumi CLI or API to get some useful information.

{{< admonition type="info" title="tl;dr" >}}
Run `pulumi whoami` with the token using the `PULUMI_ACCESS_TOKEN` environment variable. A personal token returns a human username. Organization or team tokens return a service account:

```shell
PULUMI_ACCESS_TOKEN="pul-..." pulumi whoami -v
```

You can also hit the Pulumi API directly if you don't have the Pulumi CLI installed:

```shell
curl -s -H "Authorization: token pul-..." https://api.pulumi.com/api/user
```
{{< /admonition >}}

And don't worry, none of the access tokens in my examples are working tokens.

## Investigate a Token Using the Pulumi CLI

The `pulumi whoami` command prints the identity of whatever credentials the CLI is using. These credentials may come from many places but you can override the credentials with the `PULUMI_ACCESS_TOKEN` environment variable. You can run the following to get more information about a Pulumi token. The key field you want to pay attention to is `Token type:`.

Here's an example of the output you get with a personal token:

```console
$ PULUMI_ACCESS_TOKEN="pul-2d21b66a133c81ecd8e9cf323c2b57797ad88f0f" pulumi whoami -v

Logging in using access token from PULUMI_ACCESS_TOKEN
User: nelsonfigueroa
Organizations: nelsonfigueroa, nelsonfigueroa-enterprise
Backend URL: https://app.pulumi.com/nelsonfigueroa
Token type: personal
```

A personal token will respond with the username of whoever created it along with the organizations they belong to.

Here is the output of an organization token:

```console
$ PULUMI_ACCESS_TOKEN="pul-7fa1bd334f859426acc469df89b4d99fda31c119" pulumi whoami -v

Logging in using access token from PULUMI_ACCESS_TOKEN
User: service-account:2a5e8ea0-e52e-4e5d-9ab4-8730337fe78b
Organizations: nelsonfigueroa-enterprise
Backend URL: https://app.pulumi.com/service-account:2a5e8ea0-e52e-4e5d-9ab4-8730337fe78b
Token type: organization: nelsonfigueroa-enterprise
Token name: nelsons-org-token
```

An organization token will respond with the name of the organization it's associated with and the token name it's been given.

And here is what the output of a team token looks like:

```console
$ PULUMI_ACCESS_TOKEN="pul-39cbf1cd393e71681024c1eded9a79378bdadc4d" pulumi whoami -v

Logging in using access token from PULUMI_ACCESS_TOKEN
User: service-account:2a5e8ea0-e52e-4e5d-9ab4-8730337fe78b
Organizations: nelsonfigueroa-enterprise
Backend URL: https://app.pulumi.com/service-account:2a5e8ea0-e52e-4e5d-9ab4-8730337fe78b
Token type: team: test-team
Token name: test-team-token
```

A team token will also respond with the name of the organization it belongs to and the name it was given.

## Investigate a Token Using the Pulumi API

If you don't have the Pulumi CLI installed, you can hit the Pulumi API directly with `curl`.

Here is the response from a personal token (with sensitive parts redacted). For a personal token, the response is the user object of the owner of said token along with the organizations they belong to and their role in each organization:

```shell
curl -s -H "Authorization: token pul-2d21b66a133c81ecd8e9cf323c2b57797ad88f0f" https://api.pulumi.com/api/user
```

Output of the `curl` command above:

```json
{
   "id":"1867e3a0-f096-4021-b0ea-f654323863ba",
   "githubLogin":"nelsonfigueroa",
   "name":"Nelson Figueroa",
   "email":"redacted@redacted",
   "avatarUrl":"https://avatars.githubusercontent.com/u/30811275?v=4",
   "organizations":[
      {
         "name":"Nelson Figueroa",
         "githubLogin":"nelsonfigueroa",
         "avatarUrl":"https://avatars.githubusercontent.com/u/30811275?v=4",
         "role":"admin"
      },
      {
         "name":"nelsonfigueroa-enterprise",
         "githubLogin":"nelsonfigueroa-enterprise",
         "avatarUrl":"https://api.pulumi.com/static/avatars/N-E91E63.png",
         "role":"admin"
      }
   ],
   "identities":[
      
   ],
   "hasMFA":true,
   "isOrgManaged":false,
   "isManagedByMultiOrg":false
}
```

For an organization or team token, the response also includes a `tokenInfo` object with the token's name and the organization or team it belongs to. If it's an organization token, the `organization` field will be filled, but the `team` field will be `null`.

```shell
curl -s -H "Authorization: token pul-7fa1bd334f859426acc469df89b4d99fda31c119" https://api.pulumi.com/api/user
```

Output of the `curl` command above:

```json
{
   "id":"7f228f40-dc3c-4a8e-a599-f43423fa4942",
   "githubLogin":"service-account:2a5e8ea0-e52e-4e5d-9ab4-8730337fe78b",
   "name":"nelsonfigueroa-enterprise",
   "email":"527b236f-1bdb-474c-84c2-b3750f6f7384",
   "avatarUrl":"https://api.pulumi.com/static/avatars/N-E91E63.png",
   "organizations":[
      {
         "name":"nelsonfigueroa-enterprise",
         "githubLogin":"nelsonfigueroa-enterprise",
         "avatarUrl":"https://api.pulumi.com/static/avatars/N-E91E63.png",
         "role":"member"
      }
   ],
   "identities":[
      
   ],
   "tokenInfo":{
      "name":"nelsons-org-token",
      "organization":"nelsonfigueroa-enterprise",
      "team":null
   },
   "hasMFA":true,
   "isOrgManaged":false,
   "isManagedByMultiOrg":false
}
```

And if it's a team token, the `organization` field will be `null`, but the `team` field will be filled with the team name (you can still see the organization it belongs to under the `organizations` object above).

```shell
curl -s -H "Authorization: token pul-39cbf1cd393e71681024c1eded9a79378bdadc4d" https://api.pulumi.com/api/user
```

Output of the `curl` command above:

```json
{
   "id":"7f228f40-dc3c-4a8e-a599-f43423fa4942",
   "githubLogin":"service-account:2a5e8ea0-e52e-4e5d-9ab4-8730337fe78b",
   "name":"nelsonfigueroa-enterprise",
   "email":"527b236f-1bdb-474c-84c2-b3750f6f7384",
   "avatarUrl":"https://api.pulumi.com/static/avatars/N-E91E63.png",
   "organizations":[
      {
         "name":"nelsonfigueroa-enterprise",
         "githubLogin":"nelsonfigueroa-enterprise",
         "avatarUrl":"https://api.pulumi.com/static/avatars/N-E91E63.png",
         "role":"member"
      }
   ],
   "identities":[
      
   ],
   "tokenInfo":{
      "name":"test-team-token",
      "organization":null,
      "team":"test-team"
   },
   "hasMFA":true,
   "isOrgManaged":false,
   "isManagedByMultiOrg":false
}
```

## Using Token Information

This information is useful when you stumble across a Pulumi Access Token among your code or configuration. You can use the information to trace where it came from and who it belongs to.

The organization and team names can give you clues as to where a token is being used and where it comes from. Typically, tokens may be called something like `ci-deployments` or `github-actions-dev` and so on.

If you're investigating a leaked token, note that using the token in requests like these will update its last-used timestamp.

If you want to revoke any of these tokens for security reasons, there are different ways for each token.
- For organization tokens, you can revoke them under Settings > Access Management > Access Tokens.
- You can revoke team tokens under Settings > Access Management > Teams > (team name) > Access Tokens. 
- Personal tokens can only be revoked by the user that created them (under their avatar > Personal access tokens). However, if a user is compromised you can remove them from your organization and they are effectively locked out from messing with your organization even if the personal tokens are still active.

## References

- https://www.pulumi.com/docs/pulumi-cloud/access-management/access-tokens/
- https://www.pulumi.com/docs/pulumi-cloud/cloud-rest-api/
- https://www.pulumi.com/docs/reference/cloud-rest-api/users/
- https://www.pulumi.com/docs/reference/cloud-rest-api/access-tokens/
- https://www.pulumi.com/docs/iac/cli/commands/pulumi_whoami/
