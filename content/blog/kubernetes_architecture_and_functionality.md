+++
title = "Kubernetes Architecture and Functionality"
description = "Learn Kubernetes Architecture"
date = "2019-12-26"
categories = ["learning"]
tags = ["kubernetes", "minikube", "orchestration", "containers", "docker"]
draft = true
+++

## Introduction

Kubernetes (also known as k8s) is a container orchestration tool. Container orchestrators help manage many containers across several hosts. Orchestrators can...

- Provision hosts
- Instantiate containers on a host
- Restart failing containers
- Expose containers as services outside the cluster
- Scale the cluster up or down

Kubernetes is open-source and designed to automate deploying, scaling, and operating application containers.

Kubernetes is a platform to schedule and run containers on clusters of virtual machines. It runs on bare metal, virtual machines, private datacenter and public cloud.

You can use Docker containers to develop and build applications, then use Kubernetes to run these applications. You don't necessarily have to use Docker containers. There are other container platforms out there, but Docker is by far the most popular.

## Features

Kubernetes is complex and has lots of features to orchestrate containers. Here are some of the major ones:

- **Multi-Host Container Scheduling**
	- Done by kube-scheduler
	- Dssigns containers (pods) to hosts (nodes) at runtime
	- Checks resources, quality of service, policies, and user specifications before scheduling

- **Scalability and Availability**
	- Kubernetes master can be deployed in a highly available configuration
	- Multi-region deployments are available
	- Up to 5000 nodes per cluster, 100 pods per node, and 300,000 containers

- **Flexibility and Modularization**
	- Plug-andplay architecture
	- Extend architecture when needed
	- Add-ons: network drivers, service discovery, container runtime, visualization, and command

- **Registratrion & Service Discovery**
	- New worker nodes can seamlessly register themselves with the master node
	- Austomatic detection of services and endpoints via DNS or environment variables

- **Persistent Storage**
	- Pods can use persistent volumes to store data
	- Data is retained across pod restarts and crashes

- **Application Upgrades & Downgrades**
	- Rolling updates supported
	- Rollbacks supported

- **Logging & Monitoring**
	- Application monitoring built in
	- Node health checks. Failures monitored by the node controller
	- You can use existing logging frameworks 

- **Secrets Management**
	- Secrets are mounted as data volumes or environment variables
	- Secrets are specific to namespace, not shared across all applications

- **Other**
	- Kubernetes features are backward-compatible for a few versions
	- APIs are versioned


## Kubernetes Architecture 

(Image coming soon)

#### Cluster
- Contains a single master node and multiple nodes
- Each node contains its own processes
- Master node is responsible for managing the cluster

#### Master Node
- Responsible for overall management of cluster
- Has an API Server, Scheduler, Controller Manager, and a distributed key-value store called `etcd`
- API Server
	- Allows you to interact with the Kubernetes API
- Scheduler
	- Watches created pods who do not have a node assigned yet and assigns the pod to run on a specific node
- Controller Manager
	- Runs controllers, which are background threads that run tasks in a cluster
- `etcd` 
	- Is used as a database. 
	- All cluster data is stored here such as job scheduling info, pod details, etc.
- We interact with the master node using `kubectl`
- `kubectl` has a config file called `kubeconfig`. This file has server information and authentication information to access the API server.

#### Worker Nodes
- Nodes where applications operate
- Nodes can be physical or virtual machines
- Communicate back with the master node
- Worker nodes can be exposed to the internet via a load balancer
- Traffic coming into the nodes is handled by the `kube-proxy` process, which is how an end-user ends up talking to a Kubernetes application
- Worker nodes have a process called `kubelet`, a process called `kube-proxy`, and a container engine.
- `kubelet` 
	- An agent that communicates with the API server to see if pods have been assigned to the nodes. 
	- Executes pod containers via the container engine. 
	- Mounts and runs pod volume and secrets.
	- Is aware of pod and node states
	- Responds back to the master.
- `kube-proxy`
	- Network proxy and load balancer for the service on a single worker node
	- Handles network routing for TCP and UDP packets
	- Performs connection forwarding
- Container Engine
	- In our case, it's Docker.
	- Works together with `kubelet` to run containers on the node
	- You could use altenrate container engines
	- Containers of an application are tightly coupled together in a pod

#### Pods
- A pod is a group of containers inside a node
- Pods contain application containers, storage resources, a unique network IP, and options that govern how the container(s) should run
- Smallest unit that can be scheduled as a deployment in kubernetes
- Smallest unit you can interact with
- Although you can have many containers running in a pod, a pod represents one single unit of deployment, a single instance of an application
- This group of containers share storage, Linux namespace, IP addresses
- Once pods have been deployed and are running, the `kubelet` process communicates with the pods to check on state and health. The `kube-proxy` process routes any packets to the pods from other resources. 
- Pods are designed to be disposable
- Never self-heal, and are not restarted by the scheduler itself
- Never create pods just by themselves, always use higher-level constructs to manage pods like controllers (i.e. a controller like a deployment)

Lifecycle of pods:
- pending: pod has been accepted by the kubernetes system but a container has not been created yet
- running: a pod has been scheduled on a node and all of its containers are created, and at least one container is in a running state
- succeeded: all the containers in the pod have exited with an exit status of 0 (successful status, containers will not be restarted)
- failed: all the containers in the pod have exited and at least one container has failed and returned a non-zero exit status
- CrashLoopBackOff: a container fails to start for some reason, and then kubernetes tries over and over again to restart the pod

When using Kubernetes in a production setting, it's recommended to have at least a three-node cluster. For the purposes of this guide, we'll be using Minikube, which is a light Kubernetes implementation that creates a VM on your local machine and deploys a simple cluster containing only one node. We can use this to get comfortable with Kubernetes commands.

## Deployments, Jobs, and Services
This section is about what controllers are, and how they help us
Benefits of contollers:
- Application reliability
- Scaling
- Load Balancing

Kinds of controllers:
- ReplicaSets
- Deployments
- DaemonSets

ReplicaSet controller
- Has one job.
- Ensures that specified number replicas for a pod are running at all times
- if the number of pods running are less than expected (like if one crashes), the replica set controller will start up a new pod
- you can't declare a replica set by itself, you'll need to use it within a deployment

Deployment controller
- providess declarative updates for pods and ReplicaSets
- You can describe the desired state of a deployment in a YAML file and this controller will align the actual state to match
- Deployments can be defined to create new ReplicaSets or replace existing ones with new ones
- Essentially, a Deployment manages a ReplicaSet, which in turn manages a Pod
- The benefit of this architecture is that deployments can automatically support a rollback mechanism 
- A new ReplicaSet is created each time a new Deployment config is deployed, but it also keeps the old ReplicaSet for easy rollbacks

Where tf is the DaemonSets controller?? Look it up I guess

Deployment Controller Use Cases (also part of Deployment Controller section)

Pod Management: running a ReplicaSet allows us to deploy a number of pods, and heck their status as a single unit

Scaling a ReplicaSet scales out the pods, and allows for the deployment to handle more traffic

Pod updates and Rollbacks.

Pause and Resume: Used with larger changesets. Pause deployment, make changes, resume deployment. 
Pausing means that only updates are paused, but traffic will still get passed to the existing ReplicaSet (application and shit) as expected

Status
Easy way to check the health of pods and identify issues

Replication Controller is an old implementation of deployments and ReplicaSets
Use deployments and replicasets instead of replication controller

DaemonSets Controller
- ensure that all nodes run a copy of a specific pod
- As nodes are added or removed from the cluster, a DaemonSet will add or remove the required pods
- Deleting a DaemonSet will also clean up all the pods that it created
- typical use case is to run a single log aggregator or monitoring agent on a node

Jobs (is this a controller? idk)
- Supervisor process for pods carrying out batch jobs
- Run individual processes that run once and complete successfully
- Typically, jobs are run as a cronjob to run a specific process at a specific time and repeate at another time

Services (Controller again? What is this? just on its own like Job)
- Allow the communication between one set of deployments with another
- When a service is created, it is assigned a unique IP address that never changes throug the lifetime of the service
- Use a service to get pods in two deployments to talk to each other
- Really important concept because they allow one set of pods to communicate with another set (i.e. frontend Pod talks to backend pods through backend service)
- Best practice to use a service when you're trying to get two deployments to talk to each other. That way, the pod in the first deployment always has an IP that they can communicate with regardless of whether the pod IPs in the second deployment changes. A service provides an unchanging address so that the Frontend Pods can effectively talk to the Backend Pods at all times.

Kinds of Services:
- Internal: IP is only reachable within the cluster. This is the cluster IP in Kubernetes speak.
- External: endpoints available through node IP:port (called NodePort) on each node
- Load Balancer: exposes application to the public internet with a load balancer (available with a cloud provider). Only used when you are using Kubernetes in a cloud environment.

## Labels, Selectors, and Namespaces

Labels
- Key/value pairs that are attached to objects like Pods, Services, and Deployments. Labels are for users of Kubernetes (like us) to identify attributes for objects.
- They can be added at deployment time or later on.
- They can be changed at any time.
- Label keys are unique per object.

Labels aren't that powerful on their own. They become powerful with Selectors.
With labels and selectors, you can identify a specific set of objects.

Selectors
Two kinds of selectors: Equality-based and Set-based

Equality-based
- include equals ( = ) and not equals ( != )

Set-based
- include IN, NOTIN, and EXISTS operators

Labels and label selectors are typically used with a `kubectl` command to list and filter objects. 

Namespaces
- feature of Kubernetes that allows you to have multiple virtual clusters backed by the same physical cluster.
- Great for large enterprises where there are many users/teams and you want to give access to different teams but at the same time have a rough idea of who owns what in the Kubernetes environment
- Also a great way to divide cluster resources between multiple users and this can be done using resource quotas
- Provides scopes for names (must be unique in the namespace)
- "Default" namespace is created when you launch Kubernetes
- Objects placed in "default" namespace at start
- You can create new namespaces whenever you want
- When you install a newer application Kubernetes, they'll typically install in a brand new namespace so that they don't interfere with your existing cluster and cause confusion

## Kubelet and kube-proxy

Kubelet
- The "Kubernetes node agent" that runs on each node
- Has many roles
	- Communicates with the API server to see if pods have been assigned to nodes
	- Executes pod containers via a container engine
	- Mounts and runs pod volumes and secrets
	- Executes health checks to identify pod/node status and reports that back to the API server
- Works in terms of Podspec, which is just a YAML file that describes the pod
- Kubelet takes a set of Podspecs that are provided by the kube-apiserver and ensures that containers described in those Podspecs are running and healthy
- Kubelet only manages containers that were created by the APi server, not any other containers that might be running on the node
- We can also manage the kubelet without an API server but that's a more advanced topic.

kube-proxy
- The Network Proxy
- Process that runs on all worker nodes
- Reflects services as defined on each node, and can do simple network stream or round-robin forwarding across a set of backends
- Service cluster IPs and ports are currently found through `docker --link` compatible environment variables specifying ports opened by the service proxy
- Has three modes (idk if there's more by now. this is in 1.8)
	- User space mode: most common mode.
	- Iptables mode:
	- Ipvs mode
- Why these modes are important: 
	- Services are defined against the API server
	- The kube-proxy then watches the API server for addition and removal of services
	- For each new service, kube-proxy opens a randomly chosen port on the local node
	- Any connections made to that port are proxied to one of the corresponding back-end pods.
