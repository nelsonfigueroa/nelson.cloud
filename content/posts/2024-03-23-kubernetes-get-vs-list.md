+++
title = "Kubernetes RBAC: Get vs List"
summary = "The differences of get vs list RBAC verbs in Kubernetes"
date = "2024-03-23"
categories = ["Kubernetes", "Cybersecurity"]
ShowToc = true
TocOpen = true
+++

## TL;DR

Having `get` permissions to a Kubernetes object does not imply you have `list` permissions and vice versa.

This was not immediately obvious to me.

I figured that if you can list all objects of a certain kind (like secrets) you can also get individual objects and vice versa, but that's not the case.

Here's how it works using Kubernetes Secrets as an example object:

- If you only have `get` permissions, you will have to know the exact name of the secret you are trying to read beforehand. You can run a command like `kubectl get secret <secret-name>` successfully, but running `kubectl get secrets` will not work.

- If you only have `list` permissions for secrets, you can run `kubectl get secrets` and see a list of secrets. But running `kubectl get secret <secret-name>` will not work.


## Example with Minikube

I'll be demonstrating the conclusion from above using [Minikube](https://minikube.sigs.k8s.io/docs/). I'll be creating the following in K8s: a Secret, two Roles, two Service Accounts, and two RoleBindings to assign `get` and `list` RBAC permissions to each service account for testing.

Here is a very long manifest that creates the following objects:
- A namespace called `example`
- A [basic authentication secret](https://kubernetes.io/docs/concepts/configuration/secret/#basic-authentication-secret) called `credentials` in the `example` namespace
- Two service accounts. One called `service-account-1` and another called `service-account-2`
- Two Roles:
  - A role named `get-secret` that allows users associated with this role to get a secret.
  - A role named `list-secrets` that allows users to list secrets.
- Two RoleBindings:
  - A RoleBinding named `get-secrets-binding` that will associate the service account `service-account-1` with the `get-secrets` role.
  - A RoleBinding named `list-secrets-binding` that will associate the service account `service-account-2` with the `list-secrets` role.


```yaml
---
apiVersion: v1
kind: Namespace
metadata:
  name: example
  labels:
    name: example

---
apiVersion: v1
kind: Secret
metadata:
  namespace: example
  name: credentials
type: kubernetes.io/basic-auth
stringData:
  username: admin
  password: securepassword

---
  apiVersion: v1
  kind: ServiceAccount
  metadata:
    name: service-account-1
    namespace: example

---
  apiVersion: v1
  kind: ServiceAccount
  metadata:
    name: service-account-2
    namespace: example

---
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  namespace: example
  name: get-secrets
rules:
  - apiGroups: [""]
    resources: ["secrets"]
    verbs: ["get"]

---
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  namespace: example
  name: list-secrets
rules:
  - apiGroups: [""]
    resources: ["secrets"]
    verbs: ["list"]

---
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: get-secrets-binding
  namespace: example
subjects:
  - kind: ServiceAccount
    name: service-account-1
    namespace: example
roleRef:
  kind: Role
  name: get-secrets
  apiGroup: rbac.authorization.k8s.io

---
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: list-secrets-binding
  namespace: example
subjects:
  - kind: ServiceAccount
    name: service-account-2
    namespace: example
roleRef:
  kind: Role
  name: list-secrets
  apiGroup: rbac.authorization.k8s.io
```

This manifest can be saved to a YAML file and applied like so:

```shell
$ kubectl apply -f manifest.yaml

namespace/example created
secret/credentials created
serviceaccount/service-account-1 created
serviceaccount/service-account-2 created
role.rbac.authorization.k8s.io/get-secrets created
role.rbac.authorization.k8s.io/list-secrets created
rolebinding.rbac.authorization.k8s.io/get-secrets-binding created
rolebinding.rbac.authorization.k8s.io/list-secrets-binding created
```

## Testing It All Out

Now we can test the `get` and `list` RBAC verbs with our service accounts.

To summarize:
- The service account `service-account-1` has `get` permissions for secrets in the `example` namespace. This service account should not be able to list secrets but should be able to get an individual secret in the `example` namespace.
- The service account `service-account-2` has `list` permissions for secrets in the `example` namespace. This service account should not be able to get an individual secret but should be able to list all secrets in the `example` namespace.

We can use the following command formula to test the permissions set to both service accounts:
```shell
kubectl auth can-i <verb> <resource> --as=system:serviceaccount:<namespace>:<serviceaccountname> -n <namespace>
```

First lets try getting a secret under both service accounts. We expect `service-account-1` to be able to `get` a secret, but not `service-account-2`:

```shell
$ kubectl auth can-i get secrets --as=system:serviceaccount:example:service-account-1 -n example

yes
```

```shell
$ kubectl auth can-i get secrets --as=system:serviceaccount:example:service-account-2 -n example

no
```

Works as expected!

Now lets try getting the `credentials` secret we created earlier just to double check. We should get the same results as above.

```shell
$ kubectl auth can-i get secrets/credentials --as=system:serviceaccount:example:service-account-1 -n example

yes
```

```shell
$ kubectl auth can-i get secrets/credentials --as=system:serviceaccount:example:service-account-2 -n example

no
```

Works as expected.

Next lets try listing secrets under both service accounts. This time we expect `service-account-2` to be able to `list` secrets, but not `service-account-1`:

```shell
$ kubectl auth can-i list secrets --as=system:serviceaccount:example:service-account-1 -n example

no
```

```shell
$ kubectl auth can-i list secrets --as=system:serviceaccount:example:service-account-2 -n example

yes
```

Works as expected once again!

## Further Reading
- https://kubernetes.io/docs/reference/access-authn-authz/rbac/
