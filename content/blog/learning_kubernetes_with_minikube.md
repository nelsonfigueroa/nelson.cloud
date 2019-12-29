+++
title = "Learning Kubernetes with Minikube"
description = "Learn Kubernetes using Minikube"
date = "2019-12-26"
categories = ["learning"]
tags = ["kubernetes", "minikube", "orchestration", "containers", "docker"]
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
	- A pod is the smallest unit that can be scheduled as a deployment in kubernetes
	- This group of containers share storage, Linux namespace, IP addresses
- Once pods have been deployed and are running, the `kubelet` process communicates with the pods to check on state and health. The `kube-proxy` process routes any packets to the pods from other resources. 
- Worker nodes can be exposed to the internet via a load balancer
- Traffic coming into the nodes is also handled by the `kube-proxy`, which is how an end-user ends up talking to a Kubernetes application

