+++
title = "Kubernetes Architecture and Functionality"
description = "Learn Kubernetes Architecture"
date = "2020-01-01"
categories = ["learning"]
tags = ["kubernetes", "minikube", "orchestration", "containers", "docker"]
+++

## Introduction

Kubernetes, also known as "k8s", is an open-source container orchestration tool designed to automate deploying, scaling, and operating application containers. Kubernetes can provision hosts, instantiate containers on a host, restart failing containers, expose containers to the public, and scale clusters up or down. It can run on bare metal, virtual machines, or the cloud. Docker containers can be used to develop and build applications, then Kubernetes can be used to run these applications. While you can use other container engines with Kubernetes, I'll be using Docker since it is the most popular. With that being said, previous Docker experience is recommended for this post to make more sense, at the very least understanding container concepts. 

> Note: This article will be updated over time. I learned Kubernetes using an older course using v1.8. As of this writing, the lastest version of Kubernetes is v1.17.

## Kubernetes Features

Kubernetes is complex and has a lot of features that help with orchestrating containers. Below are some of the more important ones to be aware of.

### Multi-Host Container Scheduling

Container scheduling is handled by the `kube-scheduler` process. It assigns containers to hosts at runtime. It is not responsible for actually running containers, but simply assigning them. `kube-scheduler` also checks resources, quality of service, policies, and user specifications before scheduling.

### Scalability and Availability

Kubernetes is designed to be highly available. It is possible to have multi-region deployments with Kubernetes. We can have up to 5000 nodes in a single cluster, 100 pods in a node, and 300,000 containers.

### Flexibility and Modularization

Kubernetes has a plug-and-play architecture. The architecture can be extended when needed. Several add-ons exist that can extend functionality. These add-ons include network drivers, service discovery, visualization, and container runtime.

### Registration and Service Discovery

New nodes in a cluster can seamlessly register themselves with the master node. Services and endpoints are automatically detected via DNS or environment variables.

### Persistent Storage

This one is self-explanatory. Pods in Kubernetes can use persistent volumes to store data. Data is retained across pod restarts and even through pod crashes.

### Application Upgrades and Downgrades

Kubernetes makes updating and downgrading applications easy. Rolling updates are supported, which allows upgrading an application with no downtime. If for some reason a new build breaks the system, Kubernetes allows us to revert to the previous build effortlessly.

### Logging and Monitoring

Kubernetes provides built-in application monitoring as well as node health checks. You can still use existing logging frameworks with Kubernetes.

### Secrets Management

Secrets in Kubernetes are mounted as data volumes or environment variables. Secrets are specific to namespace and are not shared across all applications.

### Other

Kubernetes features are backwards-compatible for a few versions to allow teams to upgrade their infrastructure. Kubernetes APIs are versioned.

If any terminology did not make sense in this section, read on. Next up I'll be covering the Kubernetes architecture and all the important terms to know.

## Clusters, Nodes, and Pods

(Image coming soon)

The architecture of Kubernetes is quite complex. The following is Kubernetes architecture in a nutshell:

At the highest level, we have **Clusters**. Each Cluster contains a **Master Node** and several **Worker Nodes**. Worker Nodes each have **Pods**, which are just a group of containers. So the order from top to bottom is:

Cluster -> Master Node/Worker Nodes -> Pods -> Containers

Now let's do a deep-dive into each of these components.

### Clusters

Each cluster contains a single master node and multiple worker nodes. Each node containts its own processes. The master node is responsible for managing the cluster. In a production environment, it is recommended to have at least a three-node cluster in addition to the master node.

### Master Node

The master node is responsible for the overall management of the cluster. It has an API Server, a Scheduler, a Controller Manager, and a distributed key-value store called `etcd`.

- The API Server allows you to interact with the Kubernetes API.
- The scheduler watches created pods who do not ave a node assigned yet, and assigns the pod to run on a specific node.
- The Controller Manager runs controllers, which are simply background threads that run tasks in the cluster.
- The `etcd` store is used as a database for cluster datta such as job scheduling information and pod details.

We interact with the master node using the `kubectl` command in the terminal. `kubectl` has a configuration file called `kubeconfig` that has server information and authentication information to access the API server.

### Worker Nodes

These nodes are where applications operate. Worker nodes can be physical or virtual machines. They communicate back with the master node. Worker nodes can be exposed to the internet through a load balancer. There are three things running in each worker node: a process called `kubelet`, a process called `kube-proxy`, and a container engine.

- The `kubelet` process is responsible for pod management within the node. It's an agent that communicates with the API server to see if pods have been assigned to the nodes, executes pod containers via the container engine, mounts and runs pod volumes and secrets, and responds back to the master node.

- The `kube-proxy` process is a network proxy and load balancer for the service on a single worker node. It handles network routing for TCP and UDP packets and performs connection forwarding. Any traffic coming into the node is handled by this process. This is how an end-user ends up talking to a Kubernetes application.

- The Container Engine works together with the `kubelet` process to run containers on the node. Although in this post I'm using Docker, you can use other container engines. Containers of an application are tightly coupled together in a Pod.

### Pods

A pod is the smallest unit you can interact with in Kubernetes. It is also the smallest unit that can be scheduled as a deployment in Kubernetes (more on deployments in a later section). A pod is simply a group of containers inside a node that share storage, Linux namespace, and IP addresses. Pods also contain storage resources and settings that determine how containers should run. Although several containers can run in a pod, a pod represents a single unit of deployment, or a single instance of an application.

When pods are deployed and running, the `kubelet` process in the node communicates with the pods to check on state and health. The `kube-proxy` process in the node routes any packets to the pods from other resources.

Pods are designed to be disposable. They never self-heal and are not restarted by the scheduler itself. You should never create pods just by themselves, always user higher-level constructs to manage pods.

Pods have several states: pending, running, succeeded, failed, and CrashLoopBackOff

- pending: A pod as been accepted by Kubernetes but a container has not been created yet.
- running: A pod has been scheduled on a node and all of its containers have been created, and at least one container is in a running state
- succeeded: All the containers in a pod have exited with a status of 0 which means success. These containers will not be restarted.
- failed: All containers in the pod have exited and at least one container has failed and returned a non-zero exit status.
- CrashLoopBackOff: A container has failed to start and Kubernetes is repeatedly trying to restart the pod.

As I stated earler, Kubernetes is complex. There are is a lot going on to make it all work. Take some time to understand Clusters, Nodes, and Pods and their individual roles in Kubernetes.

## Controllers

Kubernetes has several controllers that watch the state of your cluster and try to change current state to the desired state when needed. I'll be covering the more important controllers.

### ReplicaSets Controller

The ReplicaSets Controller as a one job: to ensure that the specified number of replicas for a pod are running at all times. When creating a Kubernetes cluster, we can specify the number of pods (i.e. replicas) that we want. If the number of pods running are less than expected (i.e. one crashes), this controller will start up a new pod. You can't declare a ReplicaSet by itself, you'll need to use it within a Deployment.

### Deployments Controller

A Deployment provides declarative updates for pods and ReplicaSets. You can describe the desired state of a Deployment in a YAML file and the Deployments controller will modify the current state to match the desired state. Deployments can be defined to create new ReplicaSets or replace existing ones with new ones. Essentially, a Deployment manages a ReplicaSet, which in turn manages a Pod. Deployments are the reason why Kubernetes can easily perform Rollbacks. A new ReplicaSet is created each time a new Deployment configuration is deployed, but it also keeps the old ReplicaSet incase a rollback is needed.

Overall, the Deployment controller helps us with pod management. We can specify the number of pods we want through ReplicaSets and then check their status as a single unit. We can also scale out pods by scaling the ReplicaSet. This allows the deployment to handle more traffic. Additionally, the Deployments controller allows us to pause deployments, make changes, and then resume deployments. Pausing means that only updates are paused but traffic will still get passed to the existing ReplicaSet (and application) as expected.

### DaemonSets Controller

The DaemonSets controller ensure that all nodes run a copy of a specific pod. As nodes are added or removed from the cluster, a DaemonSet will add or remove the requrired pods. Deleting a DaemonSet will also clean up all the pods that it created. The typical use-case is to rin a single log aggregator or monitoring agent on a node.

### Jobs Controller

The Jobs controller is the supervisor process for pods that carry out batch jobs. It allows us to run individual processes that only run once. These are typically run as a cronjob to run a job at a specific time and repeat at another.

## Services

A Service in Kubernetes is an abstract way to expose an application running on a set of pods as a network service. Kubernetes automatically assigns pods an IP address, a single DNS name for a set of pods, and can load-balance traffic across pods.

Deployments are used to describe a state and update Pods and applications, then Services are used to expose these Pods to make them accessible by users. You can also use Services to expose two deployments and get them to talk to each other, such as frontend and backend pods.

There are four types of Services in Kubernetes

- ClusterIP: The default type of service. It exposes the service internally in the cluster and can only be reached from within the cluster.
- NodePort: This type of service exposes the service on each node's IP on a static port. A ClusterIP service is automatically created and the NodePort service will route to it. You can access the NodePort service from outside of the cluster by using the NodeIP:NodePort socket.
- LoadBalancer: This service type exposes the service externally using the load balancer of your chosen cloud provider. The external load balancer routes to NodePort and ClusterIP services which are automatically created.
- ExternalName: Maps the service to to the contents of the `externalName` field. No Proxying of any kind is set up.

Services will be easier to understand once you actually try them out.

## Labels, Selectors, and Namespaces

Labels are key-value pairs that are attached to objects like Pods, Services, and Deployments. They are used purely for convenience of users like us to identify attributes. They can be added at deployment time or later on. They can be changed at any time. Labels are unique per object. Labels are not too powerful on their own, but they become powerful with Selectors, which allow you to identify a specific set of objects.

Selectors are used to select objects by label keys and values. We can select objects using equality-base selectors such as `=` and `!=` which stand for equals and not equals, respectively. We can also use set-based selectoors which are similar to SQL, such as `in`, `notin`, and `exists`.

Labels and Selectors are typically used with a `kubectl` command to list and filter objects.

Finally, Namespaces are a feature of Kubernetes that allows you to have several virtual clusters backed by the same physical cluster. This is useful in large team settings where there are many users and teams that need access to different projects in Kubernetes. Namespaces are also a great way to divide cluster resources between multiple users using resource quotas. 

When you launch Kubernetes, it creates a default namespace simply called `default` where objects are placed. You can create new namespaces wheneveer you want. When you install a newer application in Kubernetes, it'll typically install in a new namespace so that it doesn't interfere with the existing cluster and cause confusion.

Now that all the Kubrenetes architecture, functionality, and terminology has been laid out, we can get hands-on experience so it all makes sense. We'll do this using Minikube.